<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentStatus;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorPaymentStatusWithKeyResource extends JsonResource
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
            'vendor_id' => $this->vendor_id,
            'name' => $this->name,
            'description' => $this->description,
            'colour' => $this->colour,
            'bgColorCode' => $this->colour.'20',
            'status' => $this->status,
            'is_ps_default' => $this->is_ps_default,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
            'authorizations' => $this->vendorAuthorization,
        ];
    }
}
