<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintItemStatusApiResource extends JsonResource
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
            'title' => isset($this->title)?(string)$this->title:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
