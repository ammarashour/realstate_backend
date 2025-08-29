<?php

namespace Modules\Core\Transformers\Backend\NoModel\UserReport;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

class RecentRegisterUserWithKeyResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_id' => $this->phone_id,
            'apple_id' => $this->apple_id,
            'user_phone' => $this->user_phone,
            'user_address' => $this->user_address,
            'user_about_me' => $this->user_about_me,
            'user_cover_photo' => $this->user_cover_photo,
            'role_id' => $this->role_id,
            'role_id@@name' => isset($this->role) && !empty($this->role) ? $this->role->name : '',
            'status' => $this->status,
            'is_banned' => $this->is_banned,
            'code' => $this->code,
            'overall_rating' => $this->overall_rating,
        ];
    }
}
