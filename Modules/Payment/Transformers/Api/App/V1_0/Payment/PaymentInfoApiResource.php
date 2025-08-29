<?php

namespace Modules\Payment\Transformers\Api\App\V1_0\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentInfoApiResource extends JsonResource
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
            'payment_id' => isset($this->payment_id)?(string)$this->payment_id:'',
            'core_keys_id' => isset($this->core_keys_id)?(string)$this->core_keys_id:'',
            'value' => isset($this->value)?(string)$this->value:'',
            'ui_type_id' => isset($this->ui_type_id)?(string)$this->ui_type_id:'',
            'shop_id' => isset($this->shop_id)?(string)$this->shop_id:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
