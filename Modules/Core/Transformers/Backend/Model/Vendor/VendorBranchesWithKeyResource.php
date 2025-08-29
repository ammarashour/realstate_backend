<?php

namespace Modules\Core\Transformers\Backend\Model\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorBranchesWithKeyResource extends JsonResource
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
            'id' => (string) $this->id,
            'vendor_id' => (string) $this->vendor_id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'address' => (string) $this->address,
            'description' => (string) $this->description,
            'added_user_id' => (string) $this->added_user_id,
            "added_date_str" => (string) $this->added_date->diffForHumans()
        ];
    }
}
