<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Notification;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\User\PushNotificationUser;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class PushNotificationMessageApiResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => checkAndGetValue($this, 'id'),
            'type' => (string) $this->getType(),
            'message' => isset($this->id) ? (string) $this->handleNotification()['message'] : '',
            'description' => checkAndGetValue($this, 'description'),
            'is_read' => (string) $this->getIsRead($request),
            'default_photo' => new CoreImageApiResource($this->defaultPhoto ?? []),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_date_str' => $this->getAddedDateStr(),
            'chat_flag' => checkAndGetValue($this, 'chat_flag'),
            'buyer_user_id' => checkAndGetValue($this, 'buyer_user_id'),
            'seller_user_id' => checkAndGetValue($this, 'seller_user_id'),
            'sender_name' => (string) $this->handleNotification()['sender_name'],
            'sender_cover_photo' => (string) $this->handleNotification()['sender_cover_photo'],
            'item_id' => checkAndGetValue($this, 'item_id'),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    public function getUserDetails()
    {
        $userId = ($this->chat_flag == Constants::chatFromBuyer) ? $this->buyer_user_id : $this->seller_user_id;
        $user = User::find($userId);

        return [
            'name' => ! empty($user) ? $user->name : 'Deleted User',
            'cover_photo' => ! empty($user) ? $user->user_cover_photo : '',
        ];
    }

    public function buildMessage($userName)
    {
        switch ($this->type) {
            case Constants::chatMessageType:
                return __($this->chat_noti_message, ['name' => $userName]);
            case Constants::offerAcceptedType:
            case Constants::offerRejectedType:
                $item = Item::find($this->item_id)?->title;

                return __($this->chat_noti_message, ['item' => $item]);
            default:
                return __($this->chat_noti_message);
        }
    }

    public function handleNotification()
    {
        $message = $this->message ?? '';
        $sender_name = '';
        $sender_cover_photo = '';

        if (! isset($this->message) && isset($this->chat_noti_message)) {
            $userDetails = $this->getUserDetails();
            $message = $this->buildMessage($userDetails['name']);
            $sender_name = $userDetails['name'];
            $sender_cover_photo = $userDetails['cover_photo'];
        }

        return [
            'message' => $message,
            'sender_name' => $sender_name,
            'sender_cover_photo' => $sender_cover_photo,
        ];
    }

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }

    private function getIsRead($request)
    {
        if (isset($this->chat_flag)) {
            $is_read = $this->is_read;
        } else {

            $conds1[PushNotificationUser::noti_id] = $this->id;
            $conds1[PushNotificationUser::user_id] = $request->user_id;
            $conds1[PushNotificationUser::device_token] = $request->device_token;

            $noti_message = PushNotificationUser::where($conds1)->count();
            if ($noti_message > 0) {
                $is_read = 1;
            } else {
                $is_read = 0;
            }
        }

        return $is_read;
    }

    private function getType()
    {
        if (isset($this->chat_flag) && ! empty($this->chat_flag)) {
            $type = $this->type;
        } else {
            $type = Constants::pushNotiType;
        }

        return $type;
    }
}
