<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\NoModel\VendorPaymentSetting;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Payment\Transformers\Backend\Model\PaymentInfo\PaymentInfoWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentInfo\VendorPaymentInfoWithKeyResource;

class VendorPaymentSettingWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // $vendorPaymentInfo = VendorPaymentInfo::where([VendorPaymentInfo::vendorId => $this->vendor_id, VendorPaymentInfo::paymentId => $this->payment_id])->get();
        return [
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'name' => $this->payment->name,
            'description' => $this->payment->description,
            'status' => $this->status,
            'payment_relation' => $this->payment_relation,
            // 'vendor_payment_infos' => VendorPaymentInfoWithKeyResource::collection($vendorPaymentInfo),
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
        ];
    }
}
