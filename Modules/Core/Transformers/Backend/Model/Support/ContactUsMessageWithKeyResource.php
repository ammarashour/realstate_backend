<?php

namespace Modules\Core\Transformers\Backend\Model\Support;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Backend\Model\User\UserWithKeyResource;

class ContactUsMessageWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'contact_name' => (string) $this->contact_name,
            'contact_email' => (string) $this->contact_email,
            'contact_phone' => (string) $this->contact_phone,
            'contact_message' => (string) $this->contact_message,
            'is_read' => (string) $this->is_read,
            'is_seen' => (string) $this->is_seen,
            'added_date' => (string) $this->added_date,
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => isset($this->owner)?(string) $this->owner->name:'',
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => (string) $this->updated_flag,
            "owner" => new UserWithKeyResource($this->whenLoaded('owner')),
            'authorization' => $this->authorization
        ];
    }
}
