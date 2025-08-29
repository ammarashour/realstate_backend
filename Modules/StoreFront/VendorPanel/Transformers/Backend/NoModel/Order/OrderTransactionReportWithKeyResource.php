<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\NoModel\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Payment\Transformers\Backend\Model\PaymentInfo\PaymentInfoWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentInfo\VendorPaymentInfoWithKeyResource;

class OrderTransactionReportWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $currencySymbol = $this->currency->currency_symbol;
        return [
            'id' => $this->id,
            'order_id@@order_code' => $this->order?->order_code,
            'added_user_id@@name' => $this->owner?->name,
            'total_amount' => $currencySymbol.$this->payment_amount,
            'payment_method' => $this->payment_method,
            'vendor_payment_status_id@@name' => $this->vendorPaymentStatus?->name,
            'vendor_payment_status_id@@colour' => $this->vendorPaymentStatus?->colour,
            'bgColorCode' => $this->vendorPaymentStatus?->colour . "20",
            'transaction_date' => $this->payment_date,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
        ];
    }
}
