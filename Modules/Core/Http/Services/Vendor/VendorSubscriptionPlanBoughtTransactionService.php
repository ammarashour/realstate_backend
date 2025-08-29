<?php

namespace Modules\Core\Http\Services\Vendor;

use stdClass;
use Carbon\Carbon;
use App\Models\User;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Core\Http\Services\VendorService;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Entities\SubscriptionBoughtTransaction;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Http\Contracts\Vendor\VendorSubscriptionPlanBoughtTransactionServiceInterface;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

class VendorSubscriptionPlanBoughtTransactionService extends PsService implements VendorSubscriptionPlanBoughtTransactionServiceInterface
{
    public function __construct(
        protected UserAccessApiTokenService $userAccessApiTokenService,
        protected PaymentSettingService $paymentSettingService,
        protected VendorService $vendorService,
        protected PaymentInfoServiceInterface $paymentInfoService
    ) {}

    public function storeFromApi($request)
    {
        $paypal_result = 0;
        $stripe_result = 0;
        $razor_result = 0;
        $paystack_result = 0;
        $in_app_purchase_result = 0;
        $offline_result = 0;
        $flutterwave_result = 0;
        $package = $this->getPaymentInfoByPackageId($request['subscription_plan_id'], ['payment_attributes']);

        $attributes = $package->payment_attributes->map(function ($key, $value) {
            return [
                $key['attribute_key'] => $key['attribute_value']
            ];
        })->collapse();

        $currencyShortForm = AvailableCurrency::find($attributes["currency_id"])->currency_short_form;

        if ($request['payment_method'] == Constants::paypalPaymentMethod) {
            //User using Paypal to submit the transaction
            $payment_method = Constants::paypalPaymentMethod;
            $gateway = new \Braintree\Gateway([
                'environment' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalEnvironment)->value),
                'merchantId' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalMerchantId)->value),
                'publicKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalPublicKey)->value),
                'privateKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalPrivateKey)->value)
            ]);

            $result = $gateway->transaction()->sale([
                'amount'                => $request['price'],
                'paymentMethodNonce' => $request['payment_method_nonce'],
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);

            if ($result->success == 1) {
                $request['payment_method'] = $request['payment_method'];
                $paypal_result = $result->success;
            } else {
                $this->vendorService->destroy($request['vendor_id']);
                return responseMsgApi(__('package__api_paypal_transaction_fail', [], $request['language_symbol']), Constants::internalServerErrorStatusCode);
            }
        } else if ($request['payment_method'] == Constants::stripePaymentMethod) {
            $payment_method = Constants::stripePaymentMethod;
            //User using Stripe to submit the transaction
            $payment_method_nonce = explode('_', $request['payment_method_nonce']);

            if ($payment_method_nonce[0] == 'tok') {

                try {

                    # set stripe test key
                    \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::stripeSecretKey)->value));

                    $charge = \Stripe\Charge::create(array(
                        "amount"       => $request['price'] * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                        "currency"    => trim($currencyShortForm),
                        "source"      => $request['payment_method_nonce'],
                        "description" => __('package__api_order_desc', [], $request['language_symbol'])
                    ));

                    if ($charge->status == "succeeded") {
                        $request['payment_method'] = $request['payment_method'];
                        $stripe_result = 1;
                    } else {
                        $this->vendorService->destroy($request['vendor_id']);
                        return responseMsgApi(__('package__api_stripe_transaction_failed', [], $request['language_symbol']), Constants::internalServerErrorStatusCode);
                    }
                } catch (\Throwable $e) {
                    $this->vendorService->destroy($request['vendor_id']);
                    return responseMsgApi($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            } else if ($payment_method_nonce[0] == 'pm') {
                try {
                    \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::stripeSecretKey)->value));

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'payment_method' => $request['payment_method_nonce'],
                        'amount' => $request['price'] * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                        'currency' => trim($currencyShortForm),
                        'confirmation_method' => 'manual',
                        'confirm' => true,
                        'payment_method_types' => ['card']
                    ]);

                    if ($paymentIntent->status == "succeeded") {
                        $request['payment_method'] = $request['payment_method'];
                        $stripe_result = 1;
                    } else {
                        $this->vendorService->destroy($request['vendor_id']);
                        return responseMsgApi(__('package__api_stripe_transaction_failed', [], $request['language_symbol']), Constants::internalServerErrorStatusCode);
                    }
                } catch (\Throwable $e) {
                    $this->vendorService->destroy($request['vendor_id']);
                    return responseMsgApi($e->getMessage(), Constants::internalServerErrorStatusCode);
                }
            }
        } else if ($request['payment_method'] == Constants::razorPaymentMethod) {

            //User Using Razor
            $payment_method = Constants::razorPaymentMethod;
            $request['payment_method'] = $payment_method;
            $razor_result = 1;
        } else if ($request['payment_method'] == Constants::paystackPaymentMethod) {

            //User Using Paystack
            $payment_method = Constants::paystackPaymentMethod;
            $request['payment_method'] = $payment_method;
            $paystack_result = 1;
        } else if ($request['payment_method'] == Constants::flutterwavePaymentMethod) {
            $payment_method = Constants::flutterwavePaymentMethod;
            $request['payment_method'] = $payment_method;
            $flutterwave_result = 1;
        } else if ($request['payment_method'] == Constants::iapPaymentMethod) {

            //User Using COD
            $payment_method = Constants::iapPaymentMethod;
            $request['payment_method'] = "IAP";
            $in_app_purchase_result = 1;
        }
        if ($offline_result == 1) {
            $request['payment_method'] = $payment_method;
            $request['status'] = 0;
        }

        // save package bought transaction

        $transaction = $this->store($request);
        // dd($transaction);

        if ($paypal_result == 1 || $stripe_result == 1  || $razor_result == 1 || $paystack_result == 1 || $flutterwave_result == 1 || $in_app_purchase_result == 1) {

            $packageBoughtTransactionApiRelations = ['user', 'package'];
            $packageDetail = $this->get($transaction->id, null, $packageBoughtTransactionApiRelations);
            $objs  = PaymentAttribute::whereIn("core_keys_id", [$packageDetail->package->core_keys_id])->get();

            $attributes = $objs->map(function ($key, $value) {
                return [
                    $key['attribute_key'] => $key['attribute_value']
                ];
            })->collapse();
            $duration = intval($attributes['duration']);

            // update vendor expired date
            $vendor = $this->vendorService->getVendor($request->vendor_id);
            if (!empty($vendor->expired_date) && Carbon::parse($vendor->expired_date)->gt(now())) {
                $expired_date = Carbon::parse($vendor->expired_date)->addMonths($duration);
            } else {
                $expired_date = $packageDetail->added_date->addMonths($duration);
            }
            $vendorInfo = new \stdClass();
            $vendorInfo->expired_date = $expired_date->toDateTimeString();
            $vendorInfo->duration = $duration;
            $vendorInfo->status = Constants::vendorPendingStatus;
            $updateVendorExpiredDate = $this->vendorService->updateVendorExpiredDate($request['vendor_id'], $vendorInfo);

            if (isset($updateVendorExpiredDate['error'])) {
                return responseMsgApi(__('vendor_subscription_plan_update__api_db_error', [], $request['language_symbol']), Constants::internalServerErrorStatusCode);
            }
        }

        if (isset($transaction['error'])) {
            $this->vendorService->destroy($request['vendor_id']);
            return responseMsgApi(__('vendor_subscription_plan__api_db_error', [], $request['language_symbol']), Constants::internalServerErrorStatusCode);
        }

        return responseMsgApi(__('vendor_subscription_plan__api_success_subscription', [], $request['language_symbol']), Constants::createdStatusCode, Constants::success);
        // return new VendorSubscriptionTransactionWithKeyResource($packageDetail);
    }

    public function upgradeSubscription($request)
    {
        $paypal_result = 0;
        $stripe_result = 0;
        $razor_result = 0;
        $paystack_result = 0;
        $in_app_purchase_result = 0;
        $offline_result = 0;
        $flutterwave_result = 0;

        $data = new \stdClass();
        $data->user_id = $request->user_id;
        $data->vendor_id = $request->vendor_id;
        $data->subscription_plan_id = $request->subscription_plan_id;
        $data->razor_id = $request->razor_id;
        $data->price = $request->price;
        $data->is_paystack = $request->is_paystack;
        $data->status = 0;
        $data->transaction_id = Carbon::now()->getTimestamp();

        $package = $this->paymentInfoService->get($request->subscription_plan_id, null, ['payment_attributes']);
        $attributes = $package->payment_attributes->map(function ($key, $value) {
            return [
                $key['attribute_key'] => $key['attribute_value']
            ];
        })->collapse();
        $currencyShortForm = AvailableCurrency::find($attributes["currency_id"])->currency_short_form;

        if ($request->payment_method == Constants::paypalPaymentMethod) {
            //User using Paypal to submit the transaction
            $payment_method = Constants::paypalPaymentMethod;
            $gateway = new \Braintree\Gateway([
                'environment' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalEnvironment)->value),
                'merchantId' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalMerchantId)->value),
                'publicKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalPublicKey)->value),
                'privateKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::paypalPrivateKey)->value)
            ]);

            $result = $gateway->transaction()->sale([
                'amount'                => $request->price,
                'paymentMethodNonce' => $request->payment_method_nonce,
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);

            if ($result->success == 1) {
                $data->payment_method = $request->payment_method;
                $paypal_result = $result->success;
            } else {
                return ['error' => 'vendor_subscription__upgrade_fail'];
            }
        } else if ($request->payment_method == Constants::stripePaymentMethod) {
            $payment_method = Constants::stripePaymentMethod;
            //User using Stripe to submit the transaction
            $payment_method_nonce = explode('_', $request->payment_method_nonce);
            if ($payment_method_nonce[0] == 'tok') {

                try {
                    # set stripe test key
                    \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::stripeSecretKey)->value));

                    $charge = \Stripe\Charge::create(array(
                        "amount"       => $request->price * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                        "currency"    => trim($currencyShortForm),
                        "source"      => $request->payment_method_nonce,
                        "description" => __('package__api_order_desc', [], $request->language_symbol)
                    ));

                    if ($charge->status == "succeeded") {
                        $data->payment_method = $request->payment_method;
                        $stripe_result = 1;
                    } else {
                        return ['error' => 'vendor_subscription__upgrade_fail'];
                    }
                } catch (\Throwable $e) {
                    return ['error' => 'vendor_subscription__upgrade_fail'];
                }
            } else if ($payment_method_nonce[0] == 'pm') {
                try {
                    \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::stripeSecretKey)->value));

                    $paymentIntent = \Stripe\PaymentIntent::create([
                        'payment_method' => $request->payment_method_nonce,
                        'amount' => $request->price * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                        'currency' => trim($currencyShortForm),
                        'confirmation_method' => 'manual',
                        'confirm' => true,
                        'payment_method_types' => ['card']
                    ]);

                    if ($paymentIntent->status == "succeeded") {
                        $data->payment_method = $request->payment_method;
                        $stripe_result = 1;
                    } else {
                        return ['error' => 'vendor_subscription__upgrade_fail'];
                    }
                } catch (\Throwable $e) {
                    return ['error' => 'vendor_subscription__upgrade_fail'];
                }
            }
        } else if ($request->payment_method == Constants::razorPaymentMethod) {

            //User Using Razor
            $payment_method = Constants::razorPaymentMethod;
            $data->payment_method = $payment_method;
            $razor_result = 1;
        } else if ($request->payment_method == Constants::paystackPaymentMethod) {

            //User Using Paystack
            $payment_method = Constants::paystackPaymentMethod;
            $data->payment_method = $payment_method;
            $paystack_result = 1;
        } else if ($request->payment_method == Constants::iapPaymentMethod) {

            //User Using COD
            $payment_method = Constants::iapPaymentMethod;
            $data->payment_method = $payment_method;
            $in_app_purchase_result = 1;
        } else if ($request->payment_method == Constants::flutterwavePaymentMethod) {

            //User Using Flutterwave
            $payment_method = Constants::flutterwavePaymentMethod;
            $data->payment_method = $payment_method;
            $verify = $this->verifyTransaction($request);
            if (json_decode($verify)->status != "success") {
                return  ['error' => 'vendor_subscription__upgrade_fail'];
            }
            $flutterwave_result = 1;
        }
        if ($offline_result == 1) {
            $data->payment_method = $payment_method;
            $data->status = 0;
        }

        // save package bought transaction
        $transaction = $this->store([
            SubscriptionBoughtTransaction::userId => $request->user_id,
            SubscriptionBoughtTransaction::vendorId => $request->vendor_id,
            SubscriptionBoughtTransaction::subscriptionPlanId => $request->subscription_plan_id,
            SubscriptionBoughtTransaction::paymentMethod => $request->payment_method,
            SubscriptionBoughtTransaction::price => $request->price,
            SubscriptionBoughtTransaction::razorId => $request->razor_id,
        ]);

        if ($paypal_result == 1 || $stripe_result == 1  || $razor_result == 1 || $paystack_result == 1 || $in_app_purchase_result == 1 || $flutterwave_result == 1) {

            $packageBoughtTransactionApiRelations = ['user', 'package'];
            $packageDetail = $this->get($transaction->id, null, $packageBoughtTransactionApiRelations);

            $objs  = PaymentAttribute::whereIn("core_keys_id", [$packageDetail->package->core_keys_id])->get();

            $attributes = $objs->map(function ($key, $value) {
                return [
                    $key['attribute_key'] => $key['attribute_value']
                ];
            })->collapse();
            $duration = intval($attributes['duration']);

            // update vendor expired date
            $vendor = $this->vendorService->getVendor($request->vendor_id);
            if (!empty($vendor->expired_date) && Carbon::parse($vendor->expired_date)->gt(now())) {
                $expired_date = Carbon::parse($vendor->expired_date)->addMonths($duration);
            } else {
                $expired_date = $packageDetail->added_date->addMonths($duration);
            }
            $packageDetail->update([
                SubscriptionBoughtTransaction::expiredDate => $expired_date
            ]);
            $vendorInfo = new \stdClass();
            $vendorInfo->expired_date = $expired_date->toDateTimeString();
            $vendorInfo->duration = $duration;
            $vendorInfo->status = Constants::vendorAcceptStatus;
            $updateVendorExpiredDate = $this->vendorService->updateVendorExpiredDate($request->vendor_id, $vendorInfo);

            if (isset($updateVendorExpiredDate['error'])) {
                return ['error' => 'vendor_subscription__upgrade_fail'];
            }
        }

        if (isset($transaction['error'])) {
            return ['error' => 'vendor_subscription__upgrade_fail'];
        }

        return $transaction;
    }

    public function get($id = null, $conds = null, $relation = null)
    {
        $transaction = SubscriptionBoughtTransaction::when($id, function ($q, $id) {
            $q->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::id, $id);
        })
            ->leftJoin(PaymentInfo::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::subscriptionPlanId, '=', PaymentInfo::tableName . '.' . PaymentInfo::id)
            ->leftjoin(User::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::userId, '=', User::tableName . '.' . User::id)
            ->select(SubscriptionBoughtTransaction::tableName . '.*', User::tableName . '.' . User::name . ' as user_name', PaymentInfo::tableName . '.' . PaymentInfo::value, PaymentInfo::tableName . '.' . PaymentInfo::paymentId, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId)
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();
        return $transaction;
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $pagPerPage = null, $searchConds = null)
    {

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $transactions = SubscriptionBoughtTransaction::leftJoin(PaymentInfo::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::subscriptionPlanId, '=', PaymentInfo::tableName . '.' . PaymentInfo::id)
            // ->select('psx_package_bought_transactions.*', 'psx_payment_infos.value')
            ->leftjoin(User::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::userId, '=', User::tableName . '.' . User::id)
            ->select(SubscriptionBoughtTransaction::tableName . '.*', User::tableName . '.' . User::name . ' as user_name', PaymentInfo::tableName . '.' . PaymentInfo::value, PaymentInfo::tableName . '.' . PaymentInfo::paymentId, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($status, function ($q, $status) {
                $q->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::status, $status);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })->when($searchConds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::id, 'desc');
            });
        if ($pagPerPage) {
            $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $transactions = $transactions->get();
        }

        return $transactions;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function store($transactionData)
    {
        DB::beginTransaction();
        try {
            $transaction = new SubscriptionBoughtTransaction();
            $transaction->fill($transactionData);
            $transaction->added_user_id = Auth::user()->id;

            $transaction->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $transaction;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where('users.name', 'like', '%' . $search . '%');
            });
        }
        if (isset($conds['package_id']) && $conds['package_id']) {
            $package_filter = $conds['package_id'];
            $query->whereHas('package', function ($q) use ($package_filter) {
                $q->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::subscriptionPlanId, $package_filter);
            });
        }
        if (isset($conds['selected_date']) && $conds['selected_date']) {
            $added_date_filter = $conds['selected_date'];
            if ($added_date_filter[1] == '') {
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::addedDate, $added_date_filter);
        }
        if (isset($conds['selected_payment_method']) && $conds['selected_payment_method']) {
            $payment_method = $conds['selected_payment_method'];
            $query->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::paymentMethod, '=', $payment_method);
        }

        if (isset($conds['added_date_range']) && $conds['added_date_range']) {
            $added_date_filter = $conds['added_date_range'];
            if ($added_date_filter[1] == '') {
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::addedDate, $added_date_filter);
        }

        if (isset($conds['status'])) {

            $query->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::status, $conds['status']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else if ($conds['order_by'] == 'package_id') {

                $query->orderBy('value', $conds['order_type']);
            } else if ($conds['order_by'] == 'added_date' || $conds['order_by'] == 'expired_date') {

                $query->orderBy(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::addedDate, $conds['order_type']);
            } else if ($conds['order_by'] == 'post_count') {
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }
        return $query;
    }

    private function verifyTransaction($request)
    {
        $transactionId = $request->transaction_id;
        $flutterwaveSecretKey = trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::flutterwaveSecretKey)->value);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transactionId/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $flutterwaveSecretKey"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return $response;
    }

    private function getPaymentInfoByPackageId($id, $relations = null)
    {
        $package = PaymentInfo::when($relations, function ($query, $relations) {
            $query->with($relations);
        })->find($id);
        return $package;
    }
}
