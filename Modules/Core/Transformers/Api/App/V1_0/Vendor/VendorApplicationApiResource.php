<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorApplicationApiResource extends JsonResource
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
            'user_id' => checkAndGetValue($this, 'user_id'),
            'document' => checkAndGetValue($this, 'document'),
            'cover_letter' => checkAndGetValue($this, 'cover_letter'),
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            "is_empty_object" => checkAndGetValue($this, 'id', 1),
        ];
    }
}
