<?php

namespace Modules\Core\Http\Services\User;

use App\Exceptions\PsApiException;
use App\Http\Contracts\Notification\ChatHistoryServiceInterface;
use App\Http\Contracts\Notification\ChatNotiServiceInterface;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Contracts\User\PushNotificationReadUserServiceInterface;
use App\Http\Contracts\User\PushNotificationUserServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Modules\Core\Entities\Notification\ChatNoti;

use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use stdClass;

class PushNotificationReadUserService extends PsService implements PushNotificationReadUserServiceInterface
{
    protected $pushNotiMessageApiRelation;

    public function __construct(
        protected ChatHistoryServiceInterface $chatHistoryService,
        protected ChatNotiServiceInterface $chatNotiService,
        protected PushNotificationMessageServiceInterface $pushNotificationMessageService,
        protected PushNotificationUserServiceInterface $pushNotificationUserService,
        protected UserAccessApiTokenService $userAccessApiTokenService
    ) {
        $this->pushNotiMessageApiRelation = ['defaultPhoto'];
    }

    public function isReadFromApi($pushNotiReadUserData, $loginUserId, $headerToken, $langSymbol)
    {

        if ($pushNotiReadUserData['noti_type'] == 'PUSH_NOTI') {
            $push_noti = $this->pushNotificationMessageService->get($pushNotiReadUserData['noti_id']);
            if (! $push_noti) {
                throw new PsApiException(__('noti_read__api_invalid_noti_id', [], $langSymbol), Constants::badRequestStatusCode);
            }

            $this->handlePushNotiUserSave($pushNotiReadUserData, $loginUserId);

            $push_noti_token = $this->pushNotificationMessageService->get($pushNotiReadUserData['noti_id'], $this->pushNotiMessageApiRelation);

            return $push_noti_token;
        } else {
            $chatNoti = new stdClass;
            $chatNoti->is_read = 1;
            $chatNoti->id = $pushNotiReadUserData['noti_id'];
            $chatNoti->updated_user_id = $loginUserId;
            $chatNoti->updated_date = Carbon::now();
            $data = $this->updateChatNoti($chatNoti);

            return $data;
        }
    }

    public function isUnreadFromApi($pushNotiReadUserData, $loginUserId, $headerToken, $langSymbol)
    {

        if ($pushNotiReadUserData['noti_type'] == 'PUSH_NOTI') {
            $push_noti = $this->pushNotificationMessageService->get($pushNotiReadUserData['noti_id']);
            if (! $push_noti) {
                throw new PsApiException(__('noti_read__api_invalid_noti_id', [], $langSymbol), Constants::badRequestStatusCode);
            }

            $this->handleForeDelete($pushNotiReadUserData);

            $push_noti_token = $this->pushNotificationMessageService->get($pushNotiReadUserData['noti_id'], $this->pushNotiMessageApiRelation);

            return $push_noti_token;
        } else {
            $chatNoti = new stdClass;
            $chatNoti->is_read = 0;
            $chatNoti->id = $pushNotiReadUserData['noti_id'];
            $chatNoti->updated_user_id = $loginUserId;
            $chatNoti->updated_date = Carbon::now();
            $data = $this->updateChatNoti($chatNoti);

            return $data;
        }
    }

    public function destroyFromApi($pushNotiReadUserData, $loginUserId, $headerToken, $langSymbol)
    {

        if ($pushNotiReadUserData['noti_type'] == 'PUSH_NOTI') {

            $conds = $this->preparePushNotiUserData($pushNotiReadUserData);

            $this->handleSoftDelete($conds, $pushNotiReadUserData, $loginUserId);
        } else {
            ChatNoti::where(ChatNoti::id, $pushNotiReadUserData['noti_id'])->delete();
        }

        return responseMsgApi(
            __('core__api_noti_delete_success', [], $langSymbol),
            Constants::okStatusCode,
            Constants::success
        );
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function preparePushNotiUserData($pushNotiReadUserData)
    {
        $conds['device_token'] = $pushNotiReadUserData['device_token'];
        $conds['user_id'] = $pushNotiReadUserData['user_id'];
        $conds['noti_id'] = $pushNotiReadUserData['noti_id'];

        return $conds;
    }

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function handlePushNotiUserSave($pushNotiReadUserData, $loginUserId)
    {
        $conds = $this->preparePushNotiUserData($pushNotiReadUserData);
        $notiCount = $this->pushNotificationUserService->getAll(
            noPagination: Constants::yes,
            conds: $conds
        )->count();

        $this->savePushNotiUser($notiCount, $pushNotiReadUserData, $loginUserId);
    }

    private function handleSoftDelete($conds, $pushNotiReadUserData, $loginUserId)
    {
        $push_noti_token = $this->pushNotificationUserService->getAll(
            isSoftDel: Constants::yes,
            noPagination: Constants::yes,
            conds: $conds
        );

        $this->savePushNotiUser($push_noti_token->count(), $pushNotiReadUserData, $loginUserId);

        if ($push_noti_token->isEmpty()) {
            $push_noti_token = $this->pushNotificationUserService->get(conds: $conds);
            $push_noti_token->delete();
        }
    }

    private function savePushNotiUser($notiCount, $pushNotiReadUserData, $loginUserId)
    {
        if ($notiCount == 0) {

            try {
                $this->pushNotificationUserService->save($pushNotiReadUserData, $loginUserId);
            } catch (\Throwable $e) {
                DB::rollBack();
                throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
            }
        }
    }

    private function handleForeDelete($pushNotiReadUserData)
    {
        $conds = $this->preparePushNotiUserData($pushNotiReadUserData);
        $noti = $this->pushNotificationUserService->get(conds: $conds);
        if (!empty($noti)) {
            $noti->forceDelete();
        }
    }

    private function updateChatNoti($chatNotiData)
    {
        $chat = $this->chatNotiService->get($chatNotiData->id);
        if (isset($chatNotiData->is_read))
            $chat->is_read = $chatNotiData->is_read;

        if (isset($chatNotiData->updated_user_id))
            $chat->updated_user_id = $chatNotiData->updated_user_id;

        $chat->update();


        if ($chat->is_read == 1) {
            $conds1[ChatNoti::itemId] = $chat->item_id;
            $conds1[ChatNoti::buyerUserId] = $chat->buyer_user_id;
            $conds1[ChatNoti::sellerUserId] = $chat->seller_user_id;
            $conds1['chat_flag'] = $chat->chat_flag;
            $conds1['is_read'] = 0;
            $chats = $this->chatNotiService->getAll(conds: $conds1, noPagination: Constants::yes);
            foreach ($chats as $chatNoti) {
                $chatNoti->is_read = $chat->is_read;
                $chatNoti->update();
            }
        }


        $conds[ChatNoti::itemId] = $chat->item_id;
        $conds[ChatNoti::buyerUserId] = $chat->buyer_user_id;
        $conds[ChatNoti::sellerUserId] = $chat->seller_user_id;
        $chatHistory = $this->chatHistoryService->get(null, $conds);

        if (!empty($chatHistory)) {
            if ($chat->is_read == 1) {
                if ($chat->chat_flag == Constants::chatFromBuyer) {
                    $chatHistory->seller_unread_count = 0;
                } else if ($chat->chat_flag == Constants::chatFromSeller) {
                    $chatHistory->buyer_unread_count = 0;
                }
            } else {
                $conds1[ChatNoti::itemId] = $chat->item_id;
                $conds1[ChatNoti::buyerUserId] = $chat->buyer_user_id;
                $conds1[ChatNoti::sellerUserId] = $chat->seller_user_id;
                $conds1['chat_flag'] = $chat->chat_flag;
                $conds1['is_read'] = 0;
                $count = $this->chatNotiService->getAll(conds: $conds1, noPagination: Constants::yes)->count();

                if ($chat->chat_flag == Constants::chatFromBuyer) {
                    $chatHistory->seller_unread_count = $count;
                } else if ($chat->chat_flag == Constants::chatFromSeller) {
                    $chatHistory->buyer_unread_count = $count;
                }
            }

            $chatHistory = $this->chatHistoryService->update($chatHistory->id, $chatHistory, $chatNotiData->updated_user_id);
        }



        return $chat;
    }
}
