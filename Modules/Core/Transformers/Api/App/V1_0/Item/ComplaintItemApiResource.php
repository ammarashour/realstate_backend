<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Item\ComplaintItemStatus;
use Modules\Core\Entities\Item;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class ComplaintItemApiResource extends JsonResource
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
            'id' => isset($this->id) ? (string)$this->id : '',
            'item_id' => isset($this->item_id) ? (string)$this->item_id : '',
            'reported_user_id' => isset($this->reported_user_id) ? (string)$this->reported_user_id : '',
            'text_note' => isset($this->text_note) ? (string)$this->text_note : '',
            'reported_item_status_id' => isset($this->reported_item_status_id) ? (string)$this->reported_item_status_id : '',
            "item" => new ProductApiResource(isset($this->item) && $this->item ? $this->whenLoaded('item') : Item::where(Item::id, 0)->get()),
            "reported_user" => new UserApiResource(isset($this->reported_user) && $this->reported_user ? $this->whenLoaded('reported_user') : User::where(User::id, 0)->get()),
            "reported_item_status" => new ComplaintItemStatusApiResource(isset($this->reported_item_status) && $this->reported_item_status ? $this->whenLoaded('reported_item_status') : ComplaintItemStatus::where(ComplaintItemStatus::id, 0)->get()),
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "is_empty_object" => $this->when(!isset($this->id), 1),
        ];
    }
}
