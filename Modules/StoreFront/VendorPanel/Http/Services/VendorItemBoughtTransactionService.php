<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\ItemInfo;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Entities\UserAccessApiToken;
use Modules\Core\Http\Services\VendorService;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Modules\Core\Entities\Item\CartItem;
use Modules\Core\Http\Services\Item\CartItemService;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\VendorTransaction;

class VendorItemBoughtTransactionService extends PsService
{
    protected $orderService, $userAccessApiTokenService, $vendorPaymentSettingService, $orderItemService, $itemService, $vendorService, $cartItemService;
    public function __construct(UserAccessApiTokenService $userAccessApiTokenService, VendorPaymentSettingService $vendorPaymentSettingService, OrderItemService $orderItemService, ItemService $itemService, OrderService $orderService, VendorService $vendorService, CartItemService $cartItemService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->vendorPaymentSettingService = $vendorPaymentSettingService;
        $this->orderItemService = $orderItemService;
        $this->itemService = $itemService;
        $this->orderService = $orderService;
        $this->vendorService = $vendorService;
        $this->cartItemService = $cartItemService;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $transaction = new VendorTransaction();

            if (isset($request->user_id) && !empty($request->user_id))
                $transaction->user_id = $request->user_id;

            if (isset($request->vendor_id) && !empty($request->vendor_id))
                $transaction->vendor_id = $request->vendor_id;

            if (isset($request->order_id) && !empty($request->order_id))
                $transaction->order_id = $request->order_id;

            if (isset($request->currency_id) && !empty($request->currency_id))
                $transaction->currency_id = $request->currency_id;

            if (isset($request->payment_method) && !empty($request->payment_method))
                $transaction->payment_method = $request->payment_method;

            if (isset($request->vendor_payment_status_id) && !empty($request->vendor_payment_status_id))
                $transaction->vendor_payment_status_id = $request->vendor_payment_status_id;

            if (isset($request->payment_amount) && !empty($request->payment_amount))
                $transaction->payment_amount = $request->payment_amount;

            if (isset($request->razor_id) && !empty($request->razor_id))
                $transaction->razor_id = $request->razor_id;

            if (isset($request->is_paystack) && !empty($request->is_paystack))
                $transaction->is_paystack = $request->is_paystack;

            if (isset($request->status))
                $transaction->status = $request->status;

            if (isset($request->transaction_id))
                $transaction->transaction_id = $request->transaction_id;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $transaction->added_user_id = $request->added_user_id;
            else
                $transaction->added_user_id = Auth::user()->id;

            $transaction->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $transaction;
    }

    public function handleWhenPaymentSuccess($getItemOrders, $userId, $isSingleCheckout)
    {
        // update decreasing quantity
        foreach ($getItemOrders as $getItemOrder) {
            $itemId = $getItemOrder->item_id;
            $getItemInfo = ItemInfo::where(ItemInfo::itemId, $itemId)
                ->where(ItemInfo::coreKeysId, 'ps-itm00046')
                ->first();
            $getItemInfo->value = availableQuantityFromItem($itemId) - (int)$getItemOrder->quantity;
            $getItemInfo->update();

            // if this item of quantity will be zero, item will be sold out
            $availableQuantity = $getItemInfo->value;
            if (!$availableQuantity || (int)$availableQuantity < 1) {
                $orderedItem = $this->itemService->getItem($itemId);
                $orderedItem->is_sold_out = 1;
                $orderedItem->update();
            }
        }

        // delete all items from cart
        if (empty($isSingleCheckout)) {
            $cartItemIds = $this->cartItemService->getAll(addedUserId: $userId, noPagination: Constants::yes)->pluck("id");
            CartItem::destroy($cartItemIds);
        }
    }

    public function storeFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('vendor_item__api_create_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        } else {

            $vendorId = $request->vendor_id;
            $orderId = $request->order_id;

            $getItemOrders = $this->orderItemService->getOrderItems($orderId);
            $vendor = $this->vendorService->getVendor($vendorId, ['vendorCurrency']);
            if ($vendor->currency_id == null) {
                $msg = __('this_vendor_does_not_have_default_currency', [], $request->language_symbol);
                return responseMsgApi($msg, Constants::badRequestStatusCode);
            }

            // check quantity enough or not start
            foreach ($getItemOrders as $getItemOrder) {
                $itemId = $getItemOrder->item_id;
                if (availableQuantityFromItem($itemId) < (int)$getItemOrder->quantity) {
                    $msg = __('this_item_does_not_have_quantity_you_want', [], $request->language_symbol);
                    return responseMsgApi($msg, Constants::badRequestStatusCode);
                }
            }
            // check quantity enough or not end

            $paypal_result = 0;
            $stripe_result = 0;
            $razor_result = 0;
            $paystack_result = 0;
            $in_app_purchase_result = 0;
            $offline_result = 0;
            $flutterwave_result = 0;
            $cod_result = 0;

            $isSingleCheckout = $request->is_single_checkout;

            $data = new \stdClass();
            $data->user_id = $request->user_id;
            $data->vendor_id = $vendorId;
            $data->order_id = $orderId;
            $data->currency_id = $request->currency_id;
            $data->package_id = $request->package_id;
            $data->razor_id = $request->razor_id;
            $data->payment_amount = $request->payment_amount;
            $data->is_paystack = $request->is_paystack;

            if ($request->payment_method == constants::paypalPaymentMethod) {
                //User using Paypal to submit the transaction
                $payment_method = Constants::paypalPaymentMethod;
                $gateway = new \Braintree\Gateway([
                    'environment' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalEnvironment, vendorId: $vendorId)->value),
                    'merchantId' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalMerchantId, vendorId: $vendorId)->value),
                    'publicKey' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalPublicKey, vendorId: $vendorId)->value),
                    'privateKey' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalPrivateKey, vendorId: $vendorId)->value)
                ]);

                $result = $gateway->transaction()->sale([
                    'amount'                => $request->payment_amount,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => [
                        'submitForSettlement' => True
                    ]
                ]);

                if ($result->success == 1) {
                    $data->payment_method = $request->payment_method;
                    $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                    $paypal_result = $result->success;
                } else {
                    return responseMsgApi(__('vendor_item__api_paypal_transaction_fail', [], $request->language_symbol), Constants::internalServerErrorStatusCode);
                }
            } else if ($request->payment_method == constants::stripePaymentMethod) {
                $payment_method = Constants::stripePaymentMethod;
                //User using Stripe to submit the transaction
                $payment_method_nonce = explode('_', $request->payment_method_nonce);

                if ($payment_method_nonce[0] == 'tok') {

                    try {

                        # set stripe test key
                        \Stripe\Stripe::setApiKey(trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::stripeSecretKey, vendorId: $vendorId)->value));

                        $charge = \Stripe\Charge::create(array(
                            "amount"       => $request->payment_amount * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            "currency"    => trim($vendor->vendorCurrency->currency_short_form),
                            "source"      => $request->payment_method_nonce,
                            "description" => __('vendor_item__api_order_desc', [], $request->language_symbol)
                        ));

                        if ($charge->status == "succeeded") {
                            $data->payment_method = $request->payment_method;
                            $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi(__('vendor_item__api_stripe_transaction_failed', [], $request->language_symbol), Constants::internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {

                        return responseMsgApi($e->getMessage(), Constants::internalServerErrorStatusCode);
                    }
                } else if ($payment_method_nonce[0] == 'pm') {
                    try {
                        \Stripe\Stripe::setApiKey(trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::stripeSecretKey, vendorId: $vendorId)->value));

                        $paymentIntent = \Stripe\PaymentIntent::create([
                            'payment_method' => $request->payment_method_nonce,
                            'amount' => $request->payment_amount * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            'currency' => trim($vendor->vendorCurrency->currency_short_form),
                            'confirmation_method' => 'manual',
                            'confirm' => true,
                            'payment_method_types' => ['card']
                        ]);

                        if ($paymentIntent->status == "succeeded") {
                            $data->payment_method = $request->payment_method;
                            $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi(__('vendor_item__api_stripe_transaction_failed', [], $request->language_symbol), Constants::internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {
                        return responseMsgApi(__('vendor_item__api_stripe_transaction_failed', [], $request->language_symbol), Constants::internalServerErrorStatusCode);
                    }
                }
            } else if ($request->payment_method == constants::razorPaymentMethod) {

                //User Using Razor
                $payment_method = Constants::razorPaymentMethod;
                $data->payment_method = $payment_method;
                $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                $razor_result = 1;
            } else if ($request->payment_method == constants::paystackPaymentMethod) {

                //User Using Paystack
                $payment_method = Constants::paystackPaymentMethod;
                $data->payment_method = $payment_method;
                $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                $paystack_result = 1;
            } else if ($request->payment_method == constants::offlinePaymentMethod) {

                //User Using Offline
                $payment_method = Constants::offlinePaymentMethod;
                $data->vendor_payment_status_id = ps_constant::pendingStatusOfVendorPayment;
                $offline_result = 1;
            } else if ($request->payment_method == constants::codPaymentMethod) {

                //User Using Offline
                $payment_method = Constants::codPaymentMethod;
                $data->vendor_payment_status_id = ps_constant::pendingStatusOfVendorPayment;
                $cod_result = 1;
            } else if ($request->payment_method == constants::iapPaymentMethod) {

                //User Using COD
                $payment_method = Constants::iapPaymentMethod;
                $in_app_purchase_result = 1;
            } else if ($request->payment_method == constants::flutterwavePaymentMethod) {

                //User Using flutterwave
                $payment_method = Constants::flutterwavePaymentMethod;
                $data->payment_method = $payment_method;
                $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
                $flutterwave_result = 1;
            }
            if ($cod_result == 1) {
                $this->handleWhenPaymentSuccess($getItemOrders, $userId, $isSingleCheckout);

                $data->payment_method = $payment_method;
                // $data->vendor_payment_status_id = ps_constant::paidStatusOfVendorPayment;
            }

            // save vendor item bought transaction
            $transaction = $this->store($data);

            if ($paypal_result == 1 || $stripe_result == 1  || $razor_result == 1 || $paystack_result == 1 || $flutterwave_result == 1 || $in_app_purchase_result == 1) {

                $this->handleWhenPaymentSuccess($getItemOrders, $userId, $isSingleCheckout);

                $data->payment_method = $payment_method;
                $data->status = 1;
            }


            if (isset($transaction['error'])) {
                return responseMsgApi(__('vendor_item__api_db_error', [], $request->language_symbol), Constants::internalServerErrorStatusCode);
            } else {
                //update order is_payment_fail
                $order = Order::where(Order::id, $orderId)->first();
                $order->is_payment_fail = 0;
                $order->update();
            }

            // preparing data for mail start
            $relation = [
                'orderStatus' => function ($query) use ($vendorId) {
                    $query->where("vendor_id", $vendorId);
                },
                'orderItems' => ['item'],
                'shippingAndBilling',
                'vendorTransaction' => [
                    'vendorPaymentStatus' => function ($query) use ($vendorId) {
                        $query->where("vendor_id", $vendorId);
                    },
                    'currency',
                    'vendor'
                ]
            ];

            $mailData = $this->orderService->getOrder($orderId, $relation);
            // preparing data for mail end

            // send Mail to buyer (vendor owner) and seller
            sendVendorOwnerForSellingItemMail($mailData);
            sendBuyerForOrderSummaryMail($mailData);

            return responseMsgApi(__('vendor_item__api_success_bought', [], $request->language_symbol), Constants::createdStatusCode, Constants::successStatus);
        }
    }

    public function vendorPaypalNonceGenegrate($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('payment__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        } else {
            $vendorId = $request->vendor_id;

            $gateway = new \Braintree\Gateway([
                'environment' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalEnvironment, vendorId: $vendorId)->value),
                'merchantId' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalMerchantId, vendorId: $vendorId)->value),
                'publicKey' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalPublicKey, vendorId: $vendorId)->value),
                'privateKey' => trim($this->vendorPaymentSettingService->getVendorPaymentInfo(null, null, Constants::paypalPrivateKey, vendorId: $vendorId)->value)
            ]);

            $clientToken = $gateway->clientToken()->generate();

            if ($clientToken != '') {
                return responseMsgApi($clientToken, Constants::okStatusCode, Constants::successStatus);
            } else {
                // no data db
                return responseMsgApi(__('token_not_found', [], $request->language_symbol), Constants::noContentStatusCode, Constants::successStatus);
            }
        }
    }
}
