<?php

namespace Modules\Core\Transformers\Api\App\V1_0\AppInfo;

use App\Models\User;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Entities\Configuration\PhoneCountryCode;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;
use Modules\Payment\Entities\PaymentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Http\Facades\BackendSettingFacade;
use Modules\Core\Http\Facades\FrontendSettingFacade;
use Modules\Core\Http\Facades\MobileSettingFacade;
use Modules\Core\Http\Facades\SystemConfigFacade;
use Modules\Core\Transformers\Api\App\V1_0\Financial\ItemCurrencyApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorSettingApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Configuration\SystemConfigApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Configuration\MobileSettingApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Configuration\FrontendSettingApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Configuration\PhoneCountryCodeApiResource;

class AppInfoApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $in_app_purchased_prd_id_android = '';
        $in_app_purchased_prd_id_ios = '';
        $package_in_app_purchased_android = '';
        $package_in_app_purchased_ios = '';
        $noLoginUser = Constants::noLoginUser;
        $activeUser = Constants::activeUser;
        $banned = Constants::banned;
        $deleted = Constants::deleted;
        $unpublished = Constants::unpublished;

        $backendSetting = BackendSettingFacade::get();
        $frontendSetting = FrontendSettingFacade::get();
        $mobileSetting = MobileSettingFacade::get();
        $systemConfig = SystemConfigFacade::get();

        $packageIAP = PaymentInfo::select('payment_id', 'core_keys_id')->where(PaymentInfo::paymentId, Constants::packageInAppPurchasePaymentId)->with('core_key')->get();
        foreach ($packageIAP as $package) {
            $conds[PaymentAttribute::paymentId] = $package->payment_id;
            $conds[PaymentAttribute::coreKeysId] = $package->core_keys_id;
            $conds[PaymentAttribute::attributeKey] = Constants::pmtAttrPackageIapStatusCol;
            $statusAttr = PaymentAttribute::select('attribute_value')->where($conds)->first();
            if (!empty($statusAttr) && $statusAttr->attribute_value == 1) {
                $conds1[PaymentAttribute::paymentId] = $package->payment_id;
                $conds1[PaymentAttribute::coreKeysId] = $package->core_keys_id;
                $conds1[PaymentAttribute::attributeKey] = Constants::pmtAttrPackageIapTypeCol;
                $typeAttr = PaymentAttribute::select('attribute_value')->where($conds1)->first();
                if (!empty($typeAttr)) {
                    $type = $typeAttr->attribute_value;
                    if ($type == Constants::iapTypeAndroid) {
                        if ($package->core_key != null) {
                            $package_in_app_purchased_android .= $package->core_key->name . '##';
                        }
                    } else {
                        if ($package->core_key != null) {
                            $package_in_app_purchased_ios .= $package->core_key->name . '##';
                        }
                    }
                }
            }
        }
        // exit;

        $promoteIAP = PaymentInfo::where(PaymentInfo::paymentId, Constants::promotionInAppPurchasePaymentId)->with('core_key')->get();
        foreach ($promoteIAP as $promote) {
            $conds[PaymentAttribute::paymentId] = $promote->payment_id;
            $conds[PaymentAttribute::coreKeysId] = $promote->core_keys_id;
            $conds[PaymentAttribute::attributeKey] = Constants::pmtAttrPromoteIapStatusCol;
            $statusAttr = PaymentAttribute::select('attribute_value')->where($conds)->first();
            if (!empty($statusAttr) && $statusAttr->attribute_value == 1) {
                $conds1[PaymentAttribute::paymentId] = $promote->payment_id;
                $conds1[PaymentAttribute::coreKeysId] = $promote->core_keys_id;
                $conds1[PaymentAttribute::attributeKey] = Constants::pmtAttrPromoteIapTypeCol;
                $typeAttr = PaymentAttribute::select('attribute_value')->where($conds1)->first();
                if (!empty($typeAttr)) {
                    $type = $typeAttr->attribute_value;
                    if ($type == Constants::iapTypeAndroid) {
                        if ($promote->core_key != null) {
                            $in_app_purchased_prd_id_android .= $promote->core_key->name . '##';
                        }
                    } else {
                        if ($promote->core_key != null) {
                            $in_app_purchased_prd_id_ios .= $promote->core_key->name . '##';
                        }
                    }
                }
            }
        }

        $login_user_id = $request->login_user_id;
        $user_status = new \stdClass;
        if ($login_user_id) {
            if ($login_user_id == $noLoginUser) {
                $user_status->user_status = $noLoginUser;
            } else {
                $user = User::find($login_user_id);
                if ($user) {
                    if ($user->is_banned == 1) {
                        $user_status->user_status = $banned;
                    } else if ($user->status == 0) {
                        $user_status->user_status = $unpublished;
                    } else {
                        $user_status->user_status = $activeUser;
                    }
                } else {
                    $user_status->user_status = $deleted;
                }
            }
        } else {
            $user_status->user_status = $noLoginUser;
        }


        $availableCurrency = AvailableCurrency::select('currency_symbol', 'currency_short_form')->where(AvailableCurrency::isDefault, 1)->first();
        $vendor_subcription_setting = Setting::select("setting")->where("setting_env", Constants::VENDOR_SUBSCRIPTION_CONFIG)->first();
        $jsonSetting = json_decode($vendor_subcription_setting->setting, true);
        $idValue = $jsonSetting['subscription_plan'][0]['id'];
        $noticDays = $jsonSetting['notic_days'];
        $vendorCheckoutSetting = $jsonSetting['vendor_checkout_setting'];


        $vendor_subcription = new \stdClass;
        $vendor_subcription->vendor_feature_setting = (string) $backendSetting->vendor_setting;
        $vendor_subcription->vendor_subscription_setting = $idValue;
        $vendor_subcription->vendor_expired_notic_date_in_days = $noticDays;
        $vendor_subcription->vendor_checkout_setting = $vendorCheckoutSetting;

        return [
            "user_info" => $user_status,
            "frontend_config_setting" => new FrontendSettingApiResource($frontendSetting),
            "mobile_config_setting" => new MobileSettingApiResource($mobileSetting),
            "app_setting" => new SystemConfigApiResource($systemConfig),
            "default_currency" => new ItemCurrencyApiResource(ItemCurrency::where([ItemCurrency::isDefault => 1])->first()),
            "default_phone_country_code" => new PhoneCountryCodeApiResource(PhoneCountryCode::where([PhoneCountryCode::isDefault => 1])->first()),
            "currency_symbol" => (string)$availableCurrency->currency_symbol,
            "currency_short_form" => (string)$availableCurrency->currency_short_form,
            "verification_enable" => (string)$backendSetting->email_verification_enabled,
            "upload_setting" => (string)$backendSetting->upload_setting,
            // "vendor_setting" => (string)$backendSetting->vendor_setting,
            "vendor_config" => $vendor_subcription,
            "stripe_enabled" => (string) Payment::find(Constants::stripePaymentId, ['status'])->status,
            "stripe_publishable_key" => (string) PaymentInfo::select('value')->where(PaymentInfo::coreKeysId, Constants::stripePublishableKey)->first()->value,
            "paypal_enabled" => (string) Payment::find(Constants::paypalPaymentId, ['status'])->status,
            "razor_enabled" => (string) Payment::find(Constants::razorPaymentId, ['status'])->status,
            "razor_key" => (string) PaymentInfo::select('value')->where(PaymentInfo::coreKeysId, Constants::razorKey)->first()->value,
            "paystack_enabled" => (string) Payment::find(Constants::paystackPaymentId, ['status'])->status,
            "paystack_key" => (string) PaymentInfo::select('value')->where(PaymentInfo::coreKeysId, Constants::paystackKey)->first()->value,
            "offline_enabled" => (string) Payment::find(Constants::offlinePaymentId, ['status'])->status,
            "flutterwave_enabled" => (string) Payment::find(Constants::flutterwavePaymentId, ['status'])->status,
            "flutterwave_public_key" => (string) PaymentInfo::select('value')->where(PaymentInfo::coreKeysId, Constants::flutterwavePublicKey)->first()->value,
            "promote_in_app_purchased_enable" => (string) Payment::find(Constants::promotionInAppPurchasePaymentId)->status,
            "in_app_purchased_prd_id_ios" => (string)$in_app_purchased_prd_id_ios,
            "in_app_purchased_prd_id_android" => (string)$in_app_purchased_prd_id_android,
            "package_in_app_purchased_ios" => (string)$package_in_app_purchased_ios,
            "package_in_app_purchased_android" => (string)$package_in_app_purchased_android
        ];
    }
}
