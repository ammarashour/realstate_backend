<?php

namespace Modules\Core\Transformers\Backend\NoModel\Item;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;

class OfflinePaidItemWithKeyResource extends JsonResource
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
            'title' => isset($this->item) && !empty($this->item) ? $this->item->title:'',
            'is_paid' => isset($this->item) && !empty($this->item) ? $this->item->is_paid:'',
            'category' => isset($this->item) && isset($this->item->category) && !empty($this->item->category) ? $this->item->category->name:'',
            'subcategory' => isset($this->item) && isset($this->item->subcategory) && !empty($this->item) ? $this->item->subcategory->name:'',
            'start_date' =>  $this->start_date,
            'start_timestamp' =>  $this->start_timestamp,
            "end_date" =>  $this->end_date,
            'end_timestamp' =>  $this->end_timestamp,
            'amount' =>  $this->amount,
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
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::offlinePaidItemModule, Auth::id())
        ];
    }
}
