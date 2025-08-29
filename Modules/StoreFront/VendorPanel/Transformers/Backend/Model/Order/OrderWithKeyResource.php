<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\Model\Order;

use App\Config\ps_constant;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Backend\Model\Product\ProductWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentStatus\VendorPaymentStatusWithKeyResource;

class OrderWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $subTotal = 0;
        $discount = 0;
        if($this->orderItems){
            foreach($this->orderItems as $item){
                $subTotal = $subTotal+$item->sub_total;
                $discount = $discount+$item->discount_price;
            }
        }
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_code' => $this->order_code,
            'order_status_id' => $this->order_status_id,
            'order_status_id@@name' => isset($this->orderStatus) && !empty($this->orderStatus)? $this->orderStatus->name:'',
            'order_status' => new VendorPaymentStatusWithKeyResource($this->orderStatus),
            'order_date' => $this->order_date,
            'vendor_id' => $this->vendor_id,
            'vendor' => $this->vendor,
            'total_price' => $this->total_price,
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'original_price' => $this->original_price,
            'sale_price' => $this->sale_price,
            'sub_total' => $subTotal,
            'discount_price' => $discount,
            'delivery_fee' => $this->delivery_fee,
            'vendor_payment_status_id' => isset($this->vendorTransaction) && !empty($this->vendorTransaction)? $this->vendorTransaction?->vendorPaymentStatus?->id:ps_constant::pendingStatusOfVendorPayment,
            'vendor_payment_status_id@@name' => isset($this->vendorTransaction) && !empty($this->vendorTransaction)? $this->vendorTransaction?->vendorPaymentStatus?->name:'',
            'vendor_payment_status' => new VendorPaymentStatusWithKeyResource($this->vendorTransaction?->vendorPaymentStatus),
            'vendor_transaction' => $this->vendorTransaction,
            'shipping_and_billing' => $this->shippingAndBilling,
            'order_items' => $this->orderItems,
            'user_id' => $this->user_id,
            'owner_id@@name' => isset($this->owner) && !empty($this->owner)? $this->owner->name:'',
            'owner' => $this->owner,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
            'authorizations' => $this->vendorAuthorization,
        ];
    }
}
