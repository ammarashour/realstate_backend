<?php

namespace Modules\Core\Http\Services\Notification;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Notification\ChatHistoryServiceInterface;
use App\Http\Contracts\Notification\ChatNotiServiceInterface;
use App\Http\Contracts\Notification\ChatServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Contracts\User\BlockUserServiceInterface;
use App\Http\Contracts\User\PushNotificationUserServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Transformers\Api\App\V1_0\Notification\ChatHistoryApiResource;
use Modules\Core\Http\Services\Item\ComplaintItemService;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Notification\ChatHistory;
use Modules\Core\Entities\Notification\ChatNoti;
use Modules\Core\Entities\User\BlockUser;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Http\Services\User\UserBoughtService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Services\UserService;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Notification\UnreadCountApiResource;
use Throwable;

class ChatService extends PsService implements ChatServiceInterface
{
    protected $chatApiRelations;

    public function __construct(
        protected FirebaseCloudMessagingServiceInterface $firebaseCloudMessagingService,
        protected PushNotificationMessageServiceInterface $pushNotificationMessageService,
        protected PushNotificationUserServiceInterface $pushNotificationUserService,
        protected ImageServiceInterface $imageService,
        protected ItemService $itemService,
        protected PushNotificationTokenServiceInterface $pushNotificationTokenService,
        protected UserService $userService,
        protected UserBoughtService $userBoughtService,
        protected BlockUserServiceInterface $blockUserService,
        protected ComplaintItemService $complaintItemService,
        protected SystemConfigServiceInterface $systemConfigService,
        protected ChatHistoryServiceInterface $chatHistoryService,
        protected ChatNotiServiceInterface $chatNotiService,
        protected UserAccessApiTokenService $userAccessApiTokenService
    ) {
        $this->chatApiRelations = ['item', 'buyer', 'seller', 'defaultPhoto'];
    }


    /**
     * Add Chat History
     */
    public function storeFromApi($chatData, $langSymbol, $loginUserId)
    {
        $item = $this->itemService->getItem($chatData['item_id']);

        if ($item->status == -1) {
            $message = __('chatting__api_cannot_chat', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        $conds = $this->prepareChatHistoryGetCondsData($chatData);
        $chatHistory = $this->chatHistoryService->get(conds: $conds);
        $chatNotiMessage = 'chatting__api_new_message_received';

        if (empty($chatHistory)) {

            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count

                try {
                    $chatHistoryData = $this->prepareChatHistoryData($chatData, 0, 0, 1, 0);
                    $chatHistory = $this->chatHistoryService->save($chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis

                $this->handleUserOfflineAtEmptyCondition(
                    $chatData,
                    $chatData['message'],
                    $loginUserId,
                    $langSymbol,
                    $chatNotiMessage,
                    Constants::chatMessageType
                );
            }
        } else {

            $chatHistory  = $this->chatHistoryService->get($chatHistory->id);
            $chatHistoryData['latest_chat_message'] = $chatData['message'];

            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count
                try {
                    $this->chatHistoryService->update($chatHistory->id, $chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    $message = __('chatting__api_db_error', [], $langSymbol);
                    throw new PsApiException($message, Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis
                $this->handleUserOfflineAtNotEmptyCondition(
                    $chatHistory,
                    $chatData,
                    $chatData['message'],
                    $loginUserId,
                    $langSymbol,
                    $chatNotiMessage,
                    $chatHistoryData,
                    Constants::chatMessageType
                );
            }
        }

        $chatApiRelations = $this->chatApiRelations;

        $chatHistory = new ChatHistoryApiResource($this->chatHistoryService->get($chatHistory->id, null, $chatApiRelations));

        return responseDataApi($chatHistory, Constants::createdStatusCode, Constants::successStatus);
    }

    /**
     * Update Price (Make Offer or Reject Offer)
     */
    public function updatePriceFromApi($chatData, $langSymbol, $loginUserId)
    {

        $item = $this->itemService->getItem($chatData['item_id']);
        if ($item->is_sold_out == 1 && $chatData['nego_price'] != 0) {
            $message = __('chatting__api_already_sold_out', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        $conds = $this->prepareChatHistoryGetCondsData($chatData);
        $chatHistory = $this->chatHistoryService->get(null, $conds);

        $chatHistoryData['nego_price'] = $chatData['nego_price'];
        if ($chatData['nego_price'] == 0) {
            $sendNotiMessage = __('chatting__api_offer_rejected', [], $langSymbol);
            $chatHistoryData['offer_status'] = 4;
            $noti_message = "chatting__api_offer_rejected";
            $noti_type = Constants::offerRejectedType;
        } else {
            $sendNotiMessage = __('chatting__api_make_offer', [], $langSymbol);
            $chatHistoryData['offer_status'] = 2;
            $noti_message = "chatting__api_offer_received";
            $noti_type = Constants::offerReceivedType;
        }

        if (empty($chatHistory)) {


            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count

                try {
                    $chatHistoryData = $this->prepareChatHistoryData($chatData, 0, 0, 1, 0);
                    $chatHistory = $this->chatHistoryService->save($chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    $message = __('chatting__api_db_error', [], $langSymbol);
                    throw new PsApiException($message, Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis

                $chatHistory = $this->handleUserOfflineAtEmptyCondition(
                    $chatData,
                    $sendNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $noti_message,
                    $noti_type
                );
            }
        } else {
            if ($chatHistory->offer_status != 2 && $chatHistory->is_accept != 0 && $chatData['nego_price'] == 0) {
                $message = __('chatting__api_need_make_offer', [], $langSymbol);
                throw new PsApiException($message, Constants::badRequestStatusCode);
            } elseif ($chatHistory->offer_status == 2 && $chatData['nego_price'] != 0) {
                $message = __('chatting__api_already_accept_offer', [], $langSymbol);
                throw new PsApiException($message, Constants::badRequestStatusCode);
            }

            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count

                try {
                    $chat = $this->chatHistoryService->update($chatHistory->id, $chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    $message = __('chatting__api_db_error', [], $langSymbol);
                    throw new PsApiException($message, Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis

                $this->handleUserOfflineAtNotEmptyCondition(
                    $chatHistory,
                    $chatData,
                    $sendNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $noti_message,
                    $chatHistoryData,
                    $noti_type
                );
            }
        }

        $chatApiRelations = $this->chatApiRelations;
        $chat = new ChatHistoryApiResource($this->chatHistoryService->get($chatHistory->id, null, $chatApiRelations));

        return responseDataApi($chat);
    }

    public function getChatHistoryFromApi($chatData, $langSymbol, $loginUserId)
    {
        $conds[ChatHistory::itemId] = $chatData['item_id'];
        $conds[ChatHistory::buyerUserId] = $chatData['buyer_user_id'];
        $conds[ChatHistory::sellerUserId] = $chatData['seller_user_id'];
        $chatHistory = $this->chatHistoryService->get(null, $conds, $this->chatApiRelations);

        if ($chatHistory) {
            $chatHistory = new ChatHistoryApiResource($chatHistory);
        } else {
            $chatHistory = new \stdClass();
            $chatHistory->chatItemId = $chatData['item_id'];
            $chatHistory = new ChatHistoryApiResource($chatHistory);
        }
        return responseDataApi($chatHistory, Constants::okStatusCode, Constants::successStatus);
    }

    public function chatImageDeleteFromApi($chatData, $langSymbol, $loginUserId)
    {

        $fileName = $chatData['file_name'];

        $conds['img_path'] = $fileName;
        $image = $this->imageService->get($conds);

        if (empty($image)) {
            $message = __('core__api_record_not_found', [], $langSymbol);
            throw new PsApiException($message, Constants::notFoundStatusCode);
        }

        $this->imageService->deleteAll($image->img_parent_id, Constants::chatImgType);
        return responseMsgApi(__('core__api_delete_image_success', [], $langSymbol), Constants::okStatusCode, Constants::successStatus);
    }

    public function resetCountFromApi($chatData, $langSymbol, $loginUserId)
    {
        $chatNotiConds = $this->prepareChatNotiCondsDataResetCount($chatData);
        $chatNotis = $this->chatNotiService->getAll(conds: $chatNotiConds, noPagination: Constants::yes);
        foreach ($chatNotis as $chatNoti) {
            try {
                $chatNotiData = $this->prepareChatNotiUpdateDataResetCount();
                $this->chatNotiService->update($chatNoti->id, $chatNotiData, $loginUserId);
            } catch (Throwable $e) {
                dd($e->getMessage());
                throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
            }
        }

        $chatHistoryConds = $this->prepareChatHistoryGetCondsData($chatData);
        $chatHistory = $this->chatHistoryService->get(null, $chatHistoryConds);

        if (empty($chatHistory)) {
            $message = __('chatting__api_err_chat_history_not_exist', [], $langSymbol);
            throw new PsApiException($message, Constants::notFoundStatusCode);
        }

        try {
            $chatHistoryData = $this->prepareChatHistoryUpdateDataResetCount($chatData);
            $this->chatHistoryService->update($chatHistory->id, $chatHistoryData, $loginUserId);
        } catch (Throwable $e) {
            dd($e->getMessage());
            $message = __('chatting__api_db_error', [], $langSymbol);
            throw new PsApiException($message, Constants::internalServerErrorStatusCode);
        }

        $chatHistory = new ChatHistoryApiResource($this->chatHistoryService->get($chatHistory->id, null, $this->chatApiRelations));

        return responseDataApi($chatHistory, Constants::okStatusCode, Constants::successStatus);
    }

    public function unreadCountFromApi($chatData, $langSymbol, $loginUserId)
    {
        $userId = $chatData['user_id'];
        $deviceToken = $chatData['device_token'];

        $count_object = new \stdClass();
        $count_object->buyer_unread_count = $this->buyerUnreadCount($userId);
        $count_object->seller_unread_count = $this->sellerUnreadCount($userId);
        $count_object->noti_unread_count = $this->notiUnreadCount($userId, $deviceToken, $loginUserId);

        $unreadCount = new UnreadCountApiResource($count_object);
        return responseDataApi($unreadCount, Constants::okStatusCode, Constants::successStatus);
    }

    public function getAcceptOfferFromApi($chatData, $langSymbol, $loginUserId, $limit, $offset)
    {
        $userId = $chatData['user_id'];
        $returnType = $chatData['return_type'];
        $systemConfig = $this->systemConfigService->get();

        $offerListCondsData = $this->handleOfferListCondsData($userId, $returnType, $systemConfig->is_block_user);

        $chats = $this->chatHistoryService->getAll(
            limit: $limit,
            offset: $offset,
            conds: $offerListCondsData['conds'],
            condsNotIn: $offerListCondsData['notInConds'],
            noPagination: Constants::yes
        );

        if ($chats->isEmpty()) {
            $message = __('chatting__api_record_not_found', [], $langSymbol);
            throw new PsApiException($message, Constants::noContentStatusCode, Constants::successStatus);
        }

        $chatHistories = ChatHistoryApiResource::collection($chats);

        return responseDataApi($chatHistories, Constants::okStatusCode, Constants::successStatus);
    }

    public function getBuyerSellerListFromApi($chatData, $langSymbol, $loginUserId, $limit, $offset)
    {

        $systemConfig = $this->systemConfigService->get();
        $userId = $chatData['user_id'];
        $returnType = $chatData['return_type'];

        $getBuyerSellerListCondsData = $this->handleOfferListCondsData($userId, $returnType, $systemConfig->is_block_user);

        $chatHistories = $this->chatHistoryService->getAll(
            relation: $this->chatApiRelations,
            limit: $limit,
            offset: $offset,
            conds: $getBuyerSellerListCondsData['conds'],
            condsNotIn: $getBuyerSellerListCondsData['notInConds'],
            noPagination: Constants::yes
        );

        if ($chatHistories->isEmpty()) {
            $message = __('chatting__api_record_not_found', [], $langSymbol);
            throw new PsApiException($message, Constants::noContentStatusCode, Constants::successStatus);
        }

        $chatHistories = ChatHistoryApiResource::collection($chatHistories);

        return responseDataApi($chatHistories, Constants::okStatusCode, Constants::successStatus);
    }

    public function itemSoldOutFromApi($chatData, $langSymbol, $loginUserId)
    {
        Auth::loginUsingId($loginUserId);
        $chatHistoryConds = $this->prepareChatHistoryGetCondsData($chatData);
        $chatHistoryWithConds = $this->chatHistoryService->get(null, $chatHistoryConds);

        if (empty($chatHistoryWithConds)) {
            $message = __('chatting__api_cannot_sold_out', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        if ($chatHistoryWithConds->offer_status == 4) {
            $message = __('chatting__api_already_reject_offer', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        if ($chatHistoryWithConds->offer_status == '3') {
            try {
                $this->itemService->decreaseItemQuantity($chatData['item_id'], true);
            } catch (Throwable $e) {
                throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
            }


            $chatHistory = new ChatHistoryApiResource($this->chatHistoryService->get($chatHistoryWithConds->id, null, $this->chatApiRelations));

            return responseDataApi($chatHistory, Constants::okStatusCode, Constants::successStatus);
        } else {
            $message = __('chatting__api_need_user_bought', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }
    }

    public function isUserBoughtFromApi($chatData, $langSymbol, $loginUserId)
    {

        $itemName = $this->itemService->getItem($chatData['item_id'])->title;

        /** update accept offer status */
        $chatHistoryConds = $this->prepareChatHistoryGetCondsData($chatData);
        $chat_history_data = $this->chatHistoryService->get(null, $chatHistoryConds);

        if (empty($chat_history_data)) {
            $message = __('chatting__api_need_make_offer_first', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        if ($chat_history_data->offer_status == 4) {
            $message = __('chatting__api_already_reject_offer', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        if ($chat_history_data->offer_status == '2' && $chat_history_data->is_accept == '1') {

            $chat_data['offer_status'] = 3;
            $chat_data['is_accept'] = 1;
            $chat_data['is_offer'] = 1;
            $chat_data['nego_price'] = $chat_history_data->nego_price;
            $chat_data['updated_user_id'] = $loginUserId;

            if ($chatData['is_user_online'] == 0) {
                //if user is offline, send noti and add unread count, save chat_notis
                $user = $this->userService->getUser($chatData['seller_user_id']);

                //add buyer unread count
                $chat_data['buyer_unread_count'] =  (int)$chat_history_data->buyer_unread_count + 1;

                $data = $this->prepareUserBoughtSendNotiData($langSymbol, $itemName, $chatData, $user);
                $this->sendChatNoti($data);
            }

            DB::beginTransaction();
            try {
                $this->chatHistoryService->update($chat_history_data->id, $chat_data, $loginUserId);

                /** save bought data */
                $userBoughtData = $this->prepareUserBoughtData($chatData, $loginUserId);
                $this->userBoughtService->store($userBoughtData);
                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
            }

            $chatHistory = new ChatHistoryApiResource($this->chatHistoryService->get($chat_history_data->id, null, $this->chatApiRelations));
            return responseDataApi($chatHistory, Constants::okStatusCode, Constants::successStatus);
        } else if ($chat_history_data->offer_status == '3') {
            $message = __('chatting__api_already_user_bought', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        } else {
            $message = __('chatting__api_need_accept_offer', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }
    }

    public function acceptOfferFromApi($chatData, $langSymbol, $loginUserId)
    {

        Auth::loginUsingId($loginUserId);
        $chatHistoryConds = $this->prepareChatHistoryGetCondsData($chatData);
        $chat_history_data = $this->chatHistoryService->get(null, $chatHistoryConds);

        if (empty($chat_history_data)) {
            if ($chatData['is_user_online'] == '1') {
                //if user is online, no need to send noti and no need to add unread count

                try {
                    $chatHistoryData = $this->prepareChatHistoryData($chatData, 0, 0, 2, 1);
                    $chatHistory = $this->chatHistoryService->save($chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else {
                $sendNotiMessage = __('chatting__api_accept_offer', [], $langSymbol);
                $notiMessage = 'chatting__api_offer_accepted';
                $chatData['is_accept'] = 1;

                $chatHistory = $this->handleUserOfflineAtEmptyCondition(
                    $chatData,
                    $sendNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $notiMessage,
                    Constants::offerAcceptedType
                );
            }
        } else {
            if ($chat_history_data->offer_status == 4) {
                $message = __('chatting__api_already_rejected', [], $langSymbol);
                throw new PsApiException($message, Constants::badRequestStatusCode);
            }

            if ($chat_history_data->offer_status == '2' && $chat_history_data->is_accept == 1) {
                $message = __('chatting__api_err_accept_offer', [], $langSymbol);
                throw new PsApiException($message, Constants::badRequestStatusCode);
            }

            $id = $chat_history_data->id;

            $chatHistory = $this->chatHistoryService->get($id);
            $chatHistoryData['is_accept'] = 1;
            $chatHistoryData['offer_status'] = 2;
            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count

                try {
                    $this->chatHistoryService->update($chatHistory->id, $chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis

                $sendChatNotiMessage = __('chatting__api_accept_offer', [], $langSymbol);
                $chatNotiMessage = 'chatting__api_offer_accepted';

                $this->handleUserOfflineAtNotEmptyCondition(
                    $chatHistory,
                    $chatData,
                    $sendChatNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $chatNotiMessage,
                    $chatHistoryData,
                    Constants::offerAcceptedType
                );
            }
            $this->itemService->decreaseItemQuantity($chatData['item_id']);
        }

        $chatHistory = new ChatHistoryApiResource($this->chatHistoryService->get($chatHistory->id, null, $this->chatApiRelations));

        return responseDataApi($chatHistory, Constants::okStatusCode, Constants::successStatus);
    }

    public function chatImageUploadFromApi($chatData, $langSymbol, $loginUserId, $file)
    {

        if ($chatData['type'] == Constants::chatToBuyer) {
            Auth::loginUsingId($chatData['seller_user_id']);
        } else {
            Auth::loginUsingId($chatData['buyer_user_id']);
        }

        $chatHistoriesConds = $this->prepareChatHistoryGetCondsData($chatData);
        $chat_histories = $this->chatHistoryService->getAll(conds: $chatHistoriesConds, noPagination: Constants::yes);
        $id = $chat_histories[0]['id'];

        if (count($chat_histories) == 0) {

            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count
                try {
                    $chatHistoryData = $this->prepareChatHistoryData($chatData, 0, 0, 1, 0);
                    $this->chatHistoryService->save($chatHistoryData, $loginUserId);
                } catch (Throwable $e) {
                    throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis

                $sendNotiMessage = $chatData['message'];
                $notiMessage = 'chatting__api_new_message_received';
                $chatData['offer_status'] = 1;
                $this->handleUserOfflineAtEmptyCondition(
                    $chatData,
                    $sendNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $notiMessage,
                    Constants::chatMessageType
                );
            }
        } else {

            $chatHistory = $this->chatHistoryService->get($id);

            if ($chatData['is_user_online'] == 1) {
                //if user is online, no need to send noti and no need to add unread count
                try {
                    $this->chatHistoryService->update($chatHistory->id, $chatHistory->toArray(), $loginUserId);
                } catch (Throwable $e) {
                    throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else {
                //if user is offline, send noti and add unread count, save chat_notis
                $chatHistoryData[] = "";
                $sendChatNotiMessage = __('chatting__api_chat_image', [], $langSymbol);
                $chatNotiMessage = 'chatting__api_new_message_received';
                $this->handleUserOfflineAtNotEmptyCondition(
                    $chatHistory,
                    $chatData,
                    $sendChatNotiMessage,
                    $loginUserId,
                    $langSymbol,
                    $chatNotiMessage,
                    $chatHistoryData,
                    Constants::chatMessageType
                );
            }
        }

        $imgData = $this->prepareSaveImageData($id);

        $this->imageService->save($file, $imgData);

        // save image data to core_images
        $image = new CoreImageApiResource($this->imageService->get($this->prepareGetImageCondsData($id)));

        return responseDataApi($image, Constants::createdStatusCode, Constants::successStatus);
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareSaveImageData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => "chatApi",
        ];
    }

    private function prepareGetImageCondsData($id)
    {

        $data['img_parent_id'] = $id;
        $data['img_type'] = "chatApi";
        return $data;
    }

    private function prepareChatNotiData($chatNotiData, $chatFlag, $type, $chatNotiMessage)
    {
        $chatNoti['seller_user_id'] = $chatNotiData['seller_user_id'];
        $chatNoti['buyer_user_id'] = $chatNotiData['buyer_user_id'];
        $chatNoti['item_id'] = $chatNotiData['item_id'];
        $chatNoti['type'] = $type;
        $chatNoti['chat_noti_message'] = $chatNotiMessage;
        $chatNoti['chat_flag'] = $chatFlag;
        $chatNoti['is_read'] = 0;
        $chatNoti['added_date'] = Carbon::now();

        return $chatNoti;
    }

    private function prepareChatHistoryData($chatHistoryData, $buyerUnreadCount, $sellerUnreadCount, $offerStatus, $isAccept)
    {
        $chatHistory[ChatHistory::itemId] = $chatHistoryData['item_id'];
        $chatHistory[ChatHistory::buyerUserId] = $chatHistoryData['buyer_user_id'];
        $chatHistory[ChatHistory::sellerUserId] = $chatHistoryData['seller_user_id'];
        $chatHistory[ChatHistory::latestChatMessage] = $chatHistoryData['message'] ?? null;
        $chatHistory[ChatHistory::negoPrice] = $chatHistoryData['nego_price'] ?? 0;
        if ($chatHistoryData['type'] == Constants::chatToBuyer) {
            $chatHistory['buyer_unread_count'] = $buyerUnreadCount;
        } elseif ($chatHistoryData['type'] == Constants::chatToSeller) {
            $chatHistory['seller_unread_count'] = $sellerUnreadCount;
        }
        $chatHistory[ChatHistory::offerStatus] = $offerStatus;
        $chatHistory[ChatHistory::isAccept] = $isAccept;

        return $chatHistory;
    }

    private function prepareSendChatNotiData($sendChatNotiData, $flag, $senderUserId, $receiverUserId, $chatNotiMessage)
    {
        $sendChatNoti[ChatNoti::sellerUserId] = $sendChatNotiData['seller_user_id'];
        $sendChatNoti[ChatNoti::buyerUserId] = $sendChatNotiData['buyer_user_id'];
        $sendChatNoti[ChatNoti::chatNotiMessage] = $chatNotiMessage;
        $sendChatNoti[ChatNoti::itemId] = $sendChatNotiData['item_id'];
        $sendChatNoti['sender_user_id'] = $senderUserId;
        $sendChatNoti['receiver_user_id'] = $receiverUserId;
        $sendChatNoti['flag'] = $flag;

        return $sendChatNoti;
    }

    private function prepareChatHistoryGetCondsData($chatData)
    {
        $conds[ChatHistory::itemId] = $chatData['item_id'];
        $conds[ChatHistory::buyerUserId] = $chatData['buyer_user_id'];
        $conds[ChatHistory::sellerUserId] = $chatData['seller_user_id'];
        return $conds;
    }

    private function prepareChatNotiCondsDataResetCount($chatData)
    {
        $conds1[ChatNoti::itemId] = $chatData['item_id'];
        $conds1[ChatNoti::buyerUserId] = $chatData['buyer_user_id'];
        $conds1[ChatNoti::sellerUserId] = $chatData['seller_user_id'];
        if ($chatData['type'] == Constants::chatToSeller) {
            $conds1['chat_flag'] = Constants::chatFromBuyer;
        } else {
            $conds1['chat_flag'] = Constants::chatFromSeller;
        }
        return $conds1;
    }

    private function prepareChatNotiUpdateDataResetCount()
    {
        $chatNotiData[ChatNoti::isRead] = Constants::yes;
        return $chatNotiData;
    }

    private function prepareChatHistoryUpdateDataResetCount($chatData)
    {
        $chatHistoryData[ChatHistory::itemId] = $chatData['item_id'];
        $chatHistoryData[ChatHistory::buyerUserId] = $chatData['buyer_user_id'];
        $chatHistoryData[ChatHistory::sellerUserId] = $chatData['seller_user_id'];

        if ($chatData['type'] == Constants::chatToSeller) {
            $chatHistoryData[ChatHistory::sellerUnreadCount] = 0;
        } else if ($chatData['type'] == Constants::chatToBuyer) {
            $chatHistoryData[ChatHistory::buyerUnreadCount] = 0;
        }
        return $chatHistoryData;
    }

    private function prepareCondsDataNotiUnreadCount($loginUserId)
    {
        $conds_noti['login_user_id'] = $loginUserId;
        $conds_noti['is_read'] = 0;
        return $conds_noti;
    }

    private function preparePushNotiUserCondsDataNotiUnreadCount($deviceToken, $userId, $pushNotiMessageId)
    {
        $push_noti['device_token'] = $deviceToken;
        $push_noti['user_id'] = $userId;
        $push_noti['noti_id'] = $pushNotiMessageId;

        return $push_noti;
    }

    private function prepareUserBoughtData($chatData, $loginUserId)
    {
        $bought_data = new \stdClass();
        $bought_data->item_id = $chatData['item_id'];
        $bought_data->buyer_user_id = $chatData['buyer_user_id'];
        $bought_data->seller_user_id = $chatData['seller_user_id'];
        $bought_data->added_user_id = $loginUserId;

        return $bought_data;
    }

    private function prepareUserBoughtSendNotiData($langSymbol, $itemName, $chatData, $user)
    {
        $data['message'] = __('chatting__api_you_bought', [], $langSymbol) . ' ' . $itemName;
        $data[ChatNoti::buyerUserId] = $chatData['buyer_user_id'];
        $data[ChatNoti::sellerUserId] = $chatData['seller_user_id'];
        $data['sender_name'] = $user->name;
        $data[ChatNoti::itemId] = $chatData['item_id'];
        $data['sender_profile_photo'] = $user->user_cover_photo;
        $data['flag'] = Constants::chatNotiFlag;
        $data['chat_flag'] = Constants::chatFromSeller;
        $data['receiver_user_id'] = $chatData['buyer_user_id'];
        $data['sender_user_id'] = $chatData['seller_user_id'];
        return $data;
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function handleUserOfflineAtEmptyCondition($chatData, $sendChatNotiMessage, $loginUserId, $langSymbol, $chatNotiMessage, $chatNotiType)
    {
        $message = __('chatting__api_db_error', [], $langSymbol);
        if ($chatData['type'] == Constants::chatToBuyer) {
            $chatHistoryData = $this->prepareChatHistoryData($chatData, 1, 0, 1, 0);

            // for send noti data
            $sendChatNotiData = $this->prepareSendChatNotiData(
                $chatData,
                Constants::chatFromSeller,
                $chatData[ChatNoti::sellerUserId],
                $chatData[ChatNoti::buyerUserId],
                $sendChatNotiMessage
            );

            // save noti to chat_notis
            try {
                $chatNotiData = $this->prepareChatNotiData(
                    $chatData,
                    Constants::chatFromSeller,
                    $chatNotiType,
                    $chatNotiMessage
                );
                $this->chatNotiService->save($chatNotiData, $loginUserId);
            } catch (Throwable $e) {
                throw new PsApiException($message, Constants::internalServerErrorStatusCode);
            }
        } elseif ($chatData['type'] == Constants::chatToSeller) {
            $chatHistoryData = $this->prepareChatHistoryData($chatData, 0, 1, 1, 0);

            // for send noti data
            $sendChatNotiData = $this->prepareSendChatNotiData(
                $chatData,
                Constants::chatFromBuyer,
                $chatData[ChatNoti::buyerUserId],
                $chatData[ChatNoti::sellerUserId],
                $sendChatNotiMessage
            );

            // save noti to chat_notis
            try {
                $chatNotiData = $this->prepareChatNotiData(
                    $chatData,
                    Constants::chatFromBuyer,
                    $chatNotiType,
                    $chatNotiMessage
                );
                $this->chatNotiService->save($chatNotiData, $loginUserId);
            } catch (Throwable $e) {
                throw new PsApiException($message, Constants::internalServerErrorStatusCode);
            }
        }

        try {
            $chatHistory = $this->chatHistoryService->save($chatHistoryData, $loginUserId);
        } catch (Throwable $e) {
            throw new PsApiException($message, Constants::internalServerErrorStatusCode);
        }

        $this->sendChatNoti($sendChatNotiData);

        return $chatHistory;
    }

    private function handleUserOfflineAtNotEmptyCondition($chatHistory, $chatData, $sendChatNotiMessage, $loginUserId, $langSymbol, $chatNotiMessage, $chatHistoryData, $chatNotiType)
    {
        $message = __('chatting__api_db_error', [], $langSymbol);
        if ($chatData['type'] == Constants::chatToBuyer) {
            // increase noti count
            $chatHistoryData['buyer_unread_count'] = (int)$chatHistory->buyer_unread_count + 1;

            // for send noti data
            $sendChatNotiData = $this->prepareSendChatNotiData(
                $chatData,
                Constants::chatFromSeller,
                $chatData[ChatNoti::sellerUserId],
                $chatData[ChatNoti::buyerUserId],
                $sendChatNotiMessage
            );

            // save noti to chat_notis
            try {
                $chatNotiData = $this->prepareChatNotiData($chatData, Constants::chatFromSeller, $chatNotiType, $chatNotiMessage);
                $this->chatNotiService->save($chatNotiData, $loginUserId);
            } catch (Throwable $e) {
                throw new PsApiException($message, Constants::internalServerErrorStatusCode);
            }
        } elseif ($chatData['type'] == Constants::chatToSeller) {
            // increase noti count
            $chatHistoryData['seller_unread_count'] = (int)$chatHistory->seller_unread_count + 1;

            // for send noti data
            $sendChatNotiData = $this->prepareSendChatNotiData(
                $chatData,
                Constants::chatFromBuyer,
                $chatData[ChatNoti::buyerUserId],
                $chatData[ChatNoti::sellerUserId],
                $sendChatNotiMessage
            );

            // save noti to chat_notis
            try {
                $chatNotiData = $this->prepareChatNotiData($chatData, Constants::chatFromBuyer, $chatNotiType, $chatNotiMessage);
                $this->chatNotiService->save($chatNotiData, $loginUserId);
            } catch (Throwable $e) {
                throw new PsApiException($message, Constants::internalServerErrorStatusCode);
            }
        }

        try {
            $chatHistory = $this->chatHistoryService->update($chatHistory->id, $chatHistoryData, $loginUserId);
        } catch (Throwable $e) {
            $message = __('chatting__api_db_error', [], $langSymbol);
            throw new PsApiException($message, Constants::internalServerErrorStatusCode);
        }

        $this->sendChatNoti($sendChatNotiData);
    }

    private function buyerUnreadCount($userId)
    {
        $buyer_unread_count = 0;
        $conds_buyer[ChatHistory::buyerUserId] = $userId;
        $buyer_unread_records = $this->chatHistoryService->getAll(conds: $conds_buyer, noPagination: Constants::yes);
        foreach ($buyer_unread_records as $chat) {
            $buyer_unread_count += (int)$chat->buyer_unread_count;
        }
        return $buyer_unread_count;
    }

    private function sellerUnreadCount($userId)
    {
        $seller_unread_count = 0;
        $conds_seller[ChatHistory::sellerUserId] = $userId;
        $seller_unread_records = $this->chatHistoryService->getAll(conds: $conds_seller, noPagination: Constants::yes);
        foreach ($seller_unread_records as $chat) {
            $seller_unread_count += (int)$chat->seller_unread_count;
        }

        return $seller_unread_count;
    }

    private function notiUnreadCount($userId, $deviceToken, $loginUserId)
    {
        $noti_unread_count = 0;
        $chatNotiConds = $this->prepareCondsDataNotiUnreadCount($loginUserId);
        $chatMessages = $this->chatNotiService->getAll(conds: $chatNotiConds, noPagination: Constants::yes);
        $noti_unread_count = count($chatMessages);

        $notiIds = $this->pushNotificationUserService->getAll($userId, Constants::yes, Constants::yes)->pluck("noti_id");
        $pushNotiMessages = $this->pushNotificationMessageService->getAll(notIds: $notiIds);

        foreach ($pushNotiMessages as $pushNotiMessage) {

            $pushNotiMessageConds = $this->preparePushNotiUserCondsDataNotiUnreadCount($deviceToken, $userId, $pushNotiMessage->id);
            $noti_read = $this->pushNotificationUserService->getAll(conds: $pushNotiMessageConds, noPagination: Constants::yes);
            if ($noti_read == 0) {
                $noti_unread_count = $noti_unread_count + 1;
            }
        }

        return $noti_unread_count;
    }

    private function handleOfferListCondsData($userId, $returnType, $isBlockUser)
    {
        $not_in_conds = null;
        $conds = null;
        $blockUserConds[BlockUser::fromBlockUserId] = $userId;
        $block_ids = $this->blockUserService->getAll(conds: $blockUserConds)
            ->pluck(BlockUser::toBlockUserId);

        if ($returnType == Constants::chatBuyerReturnType) {
            $conds[ChatHistory::sellerUserId] = $userId;

            if ($isBlockUser == 1 && !empty($block_ids)) {
                $not_in_conds[ChatHistory::buyerUserId] = $block_ids;
            }
        } else if ($returnType == Constants::chatSellerReturnType) {
            $conds[ChatHistory::buyerUserId] = $userId;

            if ($isBlockUser == 1 && !empty($block_ids)) {
                $not_in_conds[ChatHistory::sellerUserId] = $block_ids;
            }
        }

        /* Start For Item Report */
        $complaint_ids = $this->complaintItemService->getComplaintItemIds($userId);
        if (!empty($complaint_ids)) {
            $not_in_conds[ChatHistory::itemId] = $complaint_ids;
        }
        /* End For Item Report */

        return [
            "conds" => $conds,
            "notInConds" => $not_in_conds
        ];
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------

    public function sendChatNoti($chat_data)
    {
        // start noti send to sender user
        $token_conds['user_id'] = $chat_data['receiver_user_id'];
        $notiTokens = $this->pushNotificationTokenService->getAll(conds: $token_conds, noPagination: Constants::yes);
        $device_ids = [];
        $platform_names = [];
        foreach ($notiTokens as $token) {
            $device_ids[] = $token->device_token;
            $platform_names[] = $token->platform_name;
        }

        // get reveiver data
        $receiver = $this->userService->getUser($chat_data['receiver_user_id']);

        $user = $this->userService->getUser($chat_data['sender_user_id']);
        $data['message'] = $chat_data[ChatNoti::chatNotiMessage];
        $data[ChatNoti::buyerUserId] = $chat_data[ChatNoti::buyerUserId];
        $data[ChatNoti::sellerUserId] = $chat_data[ChatNoti::sellerUserId];
        $data['sender_name'] = $user->name;
        $data['user_name'] = $receiver->name;
        $data[ChatNoti::itemId] = $chat_data[ChatNoti::itemId];
        $data['sender_profile_photo'] = $user->user_cover_photo;
        $data['user_profile_photo'] = $receiver->user_cover_photo;
        $data['flag'] = Constants::chatNotiFlag;
        $data['chat_flag'] = $chat_data['flag'];
        foreach ($device_ids as $device_id) {
            $this->firebaseCloudMessagingService->sendAndroidFcm($device_id, $data, $platform_names);
        }
        // end noti send to sender user

    }
}
