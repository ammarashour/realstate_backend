<?php

namespace Modules\Core\Transformers\Api\App\V1_0\User;

use Illuminate\Http\Resources\Json\JsonResource;

class BlueMarkUserApiResource extends JsonResource
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
            'id' => isset($this->id)?(string)$this->id:'',
            'user_id' => isset($this->user_id)?(string)$this->user_id:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
