<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use stdClass;
use Carbon\Carbon;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Constants\Constants;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Services\VendorService;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBilling;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;

class ShippingAndBillingService extends PsService
{

    protected $userAccessApiTokenService, $vendorService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService, VendorService $vendorService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->vendorService = $vendorService;
    }

    public function storeShippingAndBillingInfoFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        // check vendor currency
        $vendor = $this->vendorService->getVendor($request->vendor_id);
        if ($vendor->currency_id == null) {
            $msg = __('this_vendor_does_not_have_default_currency', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::badRequestStatusCode);
        }

        // check quantity enough or not start
        foreach ($request->order_summary as $orderSummary) {
            $orderSummary = (object)$orderSummary;
            $itemId = $orderSummary->item_id;
            if (availableQuantityFromItem($itemId) < (int)$orderSummary->quantity) {
                $msg = __('this_item_does_not_have_quantity_you_want', [], $request->language_symbol);
                return responseMsgApi($msg, Constants::badRequestStatusCode);
            }
        }
        // check quantity enough or not end

        DB::beginTransaction();
        try {

            $shippingInfoForNextTime = $request->is_save_shipping_info_for_next_time;
            $billingInfoForNextTime = $request->is_save_billing_info_for_next_time;

            $VendorDeliverySetting = VendorDeliverySetting::where(VendorDeliverySetting::vendorId, $request->vendor_id)->first();
            if (!empty($VendorDeliverySetting)) {
                if (!empty($VendorDeliverySetting->delivery_setting)) {
                    $deliveryFee = $VendorDeliverySetting->delivery_charges;
                } else {
                    $deliveryFee = 0;
                }
            } else {
                $deliveryFee = 0;
            }

            // save in psx_orders table
            $order = new Order();
            $order->user_id = $userId;
            $order->vendor_id = $request->vendor_id;
            $order->total_price = $request->total_price;
            $order->order_status_id = ps_constant::pendingStatusOfVendorOrder;
            $order->order_code = rand(100000, 999999);
            $order->delivery_fee = $deliveryFee;
            $order->is_payment_fail = 1; // update it after transaction success
            $order->order_date = Carbon::now();
            $order->added_user_id = $userId;
            $order->save();

            // save in psx_order_items table
            foreach ($request->order_summary as $orderSummary) {
                $orderSummary = (object)$orderSummary;
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->item_id = $orderSummary->item_id;
                $orderItem->quantity = $orderSummary->quantity;
                $orderItem->original_price = $orderSummary->original_price;
                $orderItem->sale_price = $orderSummary->sale_price;
                $orderItem->sub_total = $orderSummary->sub_total;
                $orderItem->discount_price = $orderSummary->discount_price;
                $orderItem->added_user_id = $userId;
                $orderItem->save();
            }

            // save in psx_shipping_and_billings table
            $shippingAndBilling = new ShippingAndBilling();
            $shippingAndBilling->order_id = $order->id;
            $shippingAndBilling->shipping_first_name = $request->shipping_first_name;
            $shippingAndBilling->shipping_last_name = $request->shipping_last_name;
            $shippingAndBilling->shipping_email = $request->shipping_email;
            $shippingAndBilling->shipping_phone_no = $request->shipping_phone_no;
            $shippingAndBilling->shipping_address = $request->shipping_address;
            $shippingAndBilling->shipping_country = $request->shipping_country;
            $shippingAndBilling->shipping_state = $request->shipping_state;
            $shippingAndBilling->shipping_city = $request->shipping_city;
            $shippingAndBilling->shipping_postal_code = $request->shipping_postal_code;
            $shippingAndBilling->is_save_shipping_info_for_next_time = $request->is_save_shipping_info_for_next_time;
            $shippingAndBilling->billing_first_name = $request->billing_first_name;
            $shippingAndBilling->billing_last_name = $request->billing_last_name;
            $shippingAndBilling->billing_email = $request->billing_email;
            $shippingAndBilling->billing_phone_no = $request->billing_phone_no;
            $shippingAndBilling->billing_address = $request->billing_address;
            $shippingAndBilling->billing_country = $request->billing_country;
            $shippingAndBilling->billing_state = $request->billing_state;
            $shippingAndBilling->billing_city = $request->billing_city;
            $shippingAndBilling->billing_postal_code = $request->billing_postal_code;
            $shippingAndBilling->is_save_billing_info_for_next_time = $request->is_save_billing_info_for_next_time;
            $shippingAndBilling->added_user_id = $userId;
            $shippingAndBilling->save();

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();
            $msg = __('shipping_and_billing__api_store_info_error', [], $request->language_symbol);
            return responseMsgApi($e->getMessage(), Constants::badRequestStatusCode);
        }
        $data = [
            "order_id" => (string)$order->id,
        ];
        return responseDataApi($data, Constants::okStatusCode);
    }
}
