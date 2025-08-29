<?php

namespace Modules\Core\Transformers\Backend\Model\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorApplicationWithKeyResource extends JsonResource
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
            'id' => (string)$this->id,
            'vendor_id' => (string)$this->vendor_id,
            'user_id' => (string)$this->user_id,
            'document' => (string)$this->document,
            'cover_letter' => (string)$this->cover_letter,
            'added_user_id' => (string)$this->added_user_id,
            "added_date_str" => (string)$this->added_date->diffForHumans(),
        ];
    }
}
