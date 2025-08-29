<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorBranchApiResource extends JsonResource
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
            'id' => checkAndGetValue($this, 'id'),
            'vendor_id' => checkAndGetValue($this, 'vendor_id'),
            'name' => checkAndGetValue($this, 'name'),
            'email' => checkAndGetValue($this, 'email'),
            'phone' => checkAndGetValue($this, 'phone'),
            'address' => checkAndGetValue($this, 'address'),
            'description' => checkAndGetValue($this, 'description'),
            'added_user_id' => checkAndGetValue($this, 'added_user_id'),
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }
}
