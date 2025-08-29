<?php

namespace Modules\Core\Transformers\Backend\Model\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class PushNotificationMessageWithKeyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (string) checkAndGetValue($this, 'id'),
            'message' => (string) checkAndGetValue($this, 'message'),
            'description' => (string) checkAndGetValue($this, 'description'),
            'added_date' => (string) checkAndGetValue($this, 'added_date'),
            'added_user_id' => (string) checkAndGetValue($this, 'added_user_id'),
            'added_user@@name' => (string) $this->getAddedUserName(),
            'updated_user_id' => (string) checkAndGetValue($this, 'updated_user_id'),
            'updated_user@@name' => (string)$this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'cover'=> $this->cover,
            'authorization' => $this->authorization
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedUserName()
    {

        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {

        if (empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }
}
