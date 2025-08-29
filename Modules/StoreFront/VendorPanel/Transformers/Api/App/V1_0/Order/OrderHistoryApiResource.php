<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Order;

use stdClass;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class OrderHistoryApiResource extends JsonResource
{

    public function toArray($request)
    {

        $itemInfoArr = [];
        $currencySymbol = $this->vendorTransaction?->currency?->currency_symbol;
        $totalAmount = $currencySymbol.$this->total_price;

        $discountPriceArr = [];
        $subTotalPriceArr = [];

        foreach($this->orderItems as $orderItem){
            $itemInfoObj = new stdClass;
            $itemInfoObj->item_id = (string)$orderItem->item_id;
            $itemInfoObj->item_name = (string)$orderItem->item_name;
            $itemInfoObj->original_price = (string)$currencySymbol.$orderItem->original_price;
            $itemInfoObj->sale_price = (string)$currencySymbol.$orderItem->sale_price;
            $itemInfoObj->discount_price = (string)$this->vendorTransaction?->currency?->currency_symbol.$orderItem->discount_price;
            $itemInfoObj->quantity = (string)$orderItem->quantity;
            $itemInfoObj->sub_total = (string)$this->vendorTransaction?->currency?->currency_symbol.$orderItem->sub_total;
            $itemInfoObj->default_photo = new CoreImageApiResource(isset($orderItem->item->cover[0]) && $orderItem->item->cover[0] ? $orderItem->item->cover[0]: []);
            array_push($itemInfoArr, $itemInfoObj);
            array_push($discountPriceArr, $orderItem->discount_price);
            array_push($subTotalPriceArr, $orderItem->sub_total);
        }

        return [
            "id" => (string)$this->id,
            "order_code" => (string)$this->order_code,
            "order_date" => (string)\Carbon\Carbon::parse($this->order_date)->format("M d, Y"),
            "payment_status" => (string)$this->vendorTransaction?->vendorPaymentStatus?->name,
            "payment_status_color" => (string)$this->vendorTransaction?->vendorPaymentStatus?->colour,
            "order_status" => (string)$this->orderStatus?->name,
            "order_status_color" => (string)$this->orderStatus?->colour,
            "payment_date" => isset($this->vendorTransaction?->payment_date) ? (string)\Carbon\Carbon::parse($this->vendorTransaction?->payment_date)->format("M d, Y") : "",
            "vendor_name" => (string)$this->vendor?->name,
            "item_info" => $itemInfoArr,
            "item_count" => count($itemInfoArr),
            "total" => (string)$totalAmount,
        ];

    }

    // public function toResponse($request)
    // {
    //     return $this->resource->response()->getData(true);
    // }
}
