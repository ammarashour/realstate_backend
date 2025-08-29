<?php

namespace Modules\Core\Transformers\Backend\Model\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintItemWithKeyResource extends JsonResource
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
            'id' =>  $this->id,
            'item_id' =>  $this->item_id,
            'item@@title' =>  isset($this->item) && !empty($this->item) ? $this->item->title: '',
            'item@@description' =>  isset($this->item) && !empty($this->item) ? $this->item->description: '',
            'reported_user_id' =>  $this->reported_user_id,
            'reported_user_id@@name' =>  isset($this->reported_user) && !empty($this->reported_user) ? $this->reported_user->name:'',
            'reported_item_status' => $this->reported_item_status,
            'text_note' =>  $this->text_note,
            'reported_item_status_id' =>  $this->reported_item_status_id,
            'added_date' =>  $this->added_date,
            'added_user_id' =>  $this->added_user_id,
            'added_user_id@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_user_id' =>  $this->updated_user_id,
            'updated_user_id@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' =>  $this->updated_flag,
            'authorizations' => $this->authorization
        ];
    }
}
