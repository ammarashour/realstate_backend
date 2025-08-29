<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Item;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Modules\Core\Constants\Constants;

class PaidItemHistoryApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $paidItemRejected = Constants::paidItemRejected;
        $paidItemWaitingForApproval = Constants::paidItemWaitingForApproval;

        if(isset($this->item) && $this->item){
            $this->item->paid_item_id = $this->id;
        }

        $start_date = $this->start_date;
        $end_date = $this->end_date;

        if ($this->item && $this->item->is_paid == 1) {
            $paid_status = getPaidStatus($this->start_timestamp, $this->end_timestamp);
        } else if ($this->item && $this->item->is_paid == 0) {
            $paid_status = $paidItemWaitingForApproval;
        } else if($this->item && $this->item->is_paid == 2){
            $paid_status = $paidItemRejected;
        }else{
            $paid_status = getPaidStatus($this->start_timestamp, $this->end_timestamp);
        }

        return [
            'id' => isset($this->id)?(string)$this->id:'',
            'item_id' => isset($this->item_id)?(string)$this->item_id:'',
            'start_date' =>  isset($this->start_date)?(string)$this->start_date:'',
            'start_timestamp' =>  isset($this->start_timestamp)?(string)$this->start_timestamp:'',
            "end_date" =>  isset($this->end_date)?(string)$this->end_date:'',
            "end_timestamp" =>  isset($this->end_timestamp)?(string)$this->end_timestamp:'',
            'amount' =>  isset($this->amount)?(string)$this->amount:'',
            'payment_method' =>  isset($this->payment_method)?(string)$this->payment_method:'',
            'trans_code' =>  isset($this->trans_code)?(string)$this->trans_code:'',
            'status' => isset($this->status)?(string)$this->status:'',
            'added_date' => isset($this->added_date)?(string)$this->added_date:'',
            'added_user_id' => isset($this->added_user_id)?(string)$this->added_user_id:'',
            'updated_date' => isset($this->updated_date)?(string)$this->updated_date:'',
            'updated_user_id' => isset($this->updated_user_id)?(string)$this->updated_user_id:'',
            'updated_flag' => isset($this->updated_flag)?(string)$this->updated_flag:'',
            'razor_id' => isset($this->razor_id)?(string)$this->razor_id:'',
            'purchased_id' => isset($this->purchased_id)?(string)$this->purchased_id:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "paid_status" => $paid_status,
            "item" => new ProductApiResource(isset($this->item) && $this->item ? $this->whenLoaded('item'): Item::where('id', 0)->get()),
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
