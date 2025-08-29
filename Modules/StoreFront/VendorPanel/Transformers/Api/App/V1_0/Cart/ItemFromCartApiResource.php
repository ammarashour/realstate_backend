<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use stdClass;

class ItemFromCartApiResource extends JsonResource
{

    public function toArray($request)
    {
        $isCheckoutPage = $request->is_checkout_page;
        $vendorCurrencySymbol = $this->vendor->vendorCurrency?->currency_symbol;
        $vendorCurrencyShortForm = $this->vendor->vendorCurrency?->currency_short_form;
        $deliveryFee = !empty($this->vendor->vendorDeliverySetting?->delivery_setting) ? $this->vendor->vendorDeliverySetting->delivery_charges : 0;

        $discountPriceArr = [];
        $subTotalPriceArr = [];
        $itemInfoArrObj = [];
        foreach($this->cartItems as $cartItem){

            $originalPrice = $cartItem->item->original_price;
            $salePrice = $cartItem->item->price;
            $quantity = $cartItem->quantity;
            $discountPrice = $originalPrice - $salePrice;

            $itemInfoObj = new stdClass();
            $itemInfoObj->cart_item_id = (string)$cartItem->id;
            $itemInfoObj->item_id = (string)$cartItem->item_id;
            $itemInfoObj->item_name = (string)$cartItem->item->title;
            $itemInfoObj->original_price = (string)$originalPrice;
            $itemInfoObj->sale_price = (string)$salePrice;
            $itemInfoObj->discount_price = (string)round($discountPrice * $quantity, 2);
            $itemInfoObj->vendor_currency_symbol = (string)$vendorCurrencySymbol;
            $itemInfoObj->quantity = (string)$quantity;
            $itemInfoObj->available_quantity = (string)availableQuantityFromItem($cartItem->item_id);
            $itemInfoObj->sub_total = (string)round($originalPrice * $quantity, 2);
            $itemInfoObj->is_sold_out = (string)$cartItem->item->is_sold_out;
            $itemInfoObj->is_select = (string)$cartItem->is_select;
            $itemInfoObj->default_photo = new CoreImageApiResource(isset($cartItem->item->cover[0]) && $cartItem->item->cover[0] ? $cartItem->item->cover[0]: []);

            if(empty($cartItem->item->is_sold_out)){
                if(!empty($cartItem->is_select)){
                    array_push($subTotalPriceArr, $itemInfoObj->sub_total);
                    array_push($discountPriceArr, $itemInfoObj->discount_price);
                }
            }

            if(!empty($isCheckoutPage)){
                if(empty($cartItem->item->is_sold_out)){
                    if(!empty($cartItem->is_select)){
                        array_push($itemInfoArrObj, $itemInfoObj);
                    }
                }
            } else {
                array_push($itemInfoArrObj, $itemInfoObj);
            }

        }

        $allItemSubTotalFromCart = array_sum($subTotalPriceArr);
        $allItemDiscountFromCart = array_sum($discountPriceArr);

        $total = ($allItemSubTotalFromCart - $allItemDiscountFromCart) + $deliveryFee;

        return [
            'items' => $itemInfoArrObj,
            "vendor_id" => (string)$this->vendor->id,
            "vendor_name" => (string)$this->vendor->name,
            "vendor_currency_symbol" => (string)$vendorCurrencySymbol,
            "vendor_currency_short_form" => (string)$vendorCurrencyShortForm,
            "sub_total" => (string)round($allItemSubTotalFromCart, 2),
            "total_discount" => (string)round($allItemDiscountFromCart, 2),
            "delivery_fee" => (string)$deliveryFee,
            "total" => (string)round($total, 2)
        ];
    }
}
