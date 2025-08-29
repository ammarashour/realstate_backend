<?php

namespace Modules\Core\Transformers\Backend\NoModel\BuyerReport;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\User\Rating;

class HighestRatedBuyerWithKeyResource extends JsonResource
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
            'facebook_id' => $this->facebook_id,
            'google_id' => $this->google_id,
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
            "rating_count" => isset($this->id) ? (string) (Rating::where(['to_user_id' => $this->id])->count()) : '',
            'is_show_email' => $this->is_show_email,
            'is_show_phone' => $this->is_show_phone,
            'is_shop_admin' => $this->is_shop_admin,
            'is_city_admin' => $this->is_city_admin,
            'user_lat' => $this->user_lat,
            'user_lng' => $this->user_lng,
            'verify_types' => $this->verify_types,
            'added_date_timestamp' => $this->added_date_timestamp,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user_id@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'udpated_user_id@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => $this->updated_flag,
        ];
    }
}
