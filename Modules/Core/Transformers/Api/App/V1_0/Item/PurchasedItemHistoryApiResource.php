<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchasedItemHistoryApiResource extends JsonResource
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
            'item_id' => isset($this->item_id)?(string)$this->item_id:'',
            'start_date' =>  isset($this->start_date)?(string)$this->start_date:'',
            "end_date" =>  isset($this->end_date)?(string)$this->end_date:'',
            'amount' =>  isset($this->amount)?(string)$this->amount:'',
            'promoted_days' =>  isset($this->promoted_days)?(string)$this->promoted_days:'',
            'promoted_msg' =>  isset($this->promoted_days)?(string)__('itemPromotion__api_purchased_promotion', ['attribute' => $this->promoted_days]):'',
            "payment_method" => isset($this->payment_method)?(string)$this->payment_method:'',
            'status' => isset($this->status)?(string)$this->status:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
