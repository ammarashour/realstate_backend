<?php

namespace Modules\Core\Transformers\Backend\Model\Item;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Item;

class PaidItemWithKeyResource extends JsonResource
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
            'item_id' => $this->item_id,
            'item_id@@title' => isset($this->item) && !empty($this->item)? $this->item->title:'',
            'start_date' =>  $this->start_date,
            'start_timestamp' =>  $this->start_timestamp,
            "end_date" =>  $this->end_date,
            'end_timestamp' =>  $this->end_timestamp,
            'amount' =>  $this->amount,
            "promoted_days" => $this->promoted_days,
            "payment_method" => $this->payment_method,
            'status' => $this->status,
            'trans_code' => $this->trans_code,
            'razor_id' => $this->razor_id,
            "purchased_id" => $this->purchased_id,
            "paid_status" => getPaidStatus($this->start_timestamp, $this->end_timestamp),
            "added_date" =>  $this->added_date,
            "added_user_id" =>  $this->added_user_id,
            "updated_date" =>  $this->updated_date,
            "updated_user_id" =>  $this->updated_user_id,
            "updated_flag" => $this->updated_flag,
            'authorizations' => $this->authorization,
        ];
    }
}
