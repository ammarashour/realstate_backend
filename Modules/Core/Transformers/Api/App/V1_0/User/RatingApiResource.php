<?php

namespace Modules\Core\Transformers\Api\App\V1_0\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class RatingApiResource extends JsonResource
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
            'id' => checkAndGetValue($this, 'id'),
            'title' => checkAndGetValue($this, 'title'),
            'description' => checkAndGetValue($this, 'description'),
            'rating' => checkAndGetValue($this, 'rating'),
            'from_user_id' => checkAndGetValue($this, 'from_user_id'),
            'to_user_id' => checkAndGetValue($this, 'to_user_id'),
            "transaction_header_id" => checkAndGetValue($this, 'transaction_header_id'),
            'item_id' => checkAndGetValue($this, 'item_id'),
            'shop_id' => checkAndGetValue($this, 'shop_id'),
            'type' => checkAndGetValue($this, 'type'),
            "from_user" => new UserApiResource($this->fromUser ?? []),
            "to_user" => new UserApiResource($this->toUser ?? []),
            "added_date_str" => $this->getAddedDateStr(),
            "added_date" => checkAndGetValue($this, 'added_date'),
            "is_empty_object" => checkAndGetValue($this, 'id', "1"),

        ];
    }

    ////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////

    private function getAddedDateStr()
    {
        if(empty($this->added_date)){
            return '';
        }
        return $this->added_date->diffForHumans();
    }
}
