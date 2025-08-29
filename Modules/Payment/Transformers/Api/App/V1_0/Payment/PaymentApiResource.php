<?php

namespace Modules\Payment\Transformers\Api\App\V1_0\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentApiResource extends JsonResource
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
            'name' => isset($this->name)?(string)$this->name:'',
            'description' => isset($this->description)?(string)$this->description:'',
            'status' => isset($this->status)?(string)$this->status:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
