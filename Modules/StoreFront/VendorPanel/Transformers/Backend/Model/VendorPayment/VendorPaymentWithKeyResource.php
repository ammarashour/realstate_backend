<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPayment;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\Backend\Model\Payment\PaymentWithKeyResource;

class VendorPaymentWithKeyResource extends JsonResource
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
            'payment_id' => $this->payment_id,
            'payment_id@@name' => isset($this->payment) && !empty($this->payment)? $this->payment->name:'',
            'vendor_id' => $this->vendor_id,
            'status' => $this->status,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
            'payment' => new PaymentWithKeyResource($this->payment),
            'authorizations' => $this->vendorAuthorization,
        ];
    }
}
