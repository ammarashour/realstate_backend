<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Vendor;

use stdClass;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\VendorPayment;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Core\Http\Services\VendorService;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;

class VendorConfigInfoApiResource extends JsonResource
{

    public function toArray($request)
    {
        $currency_id = Vendor::where(Vendor::id, $request->vendor_id)->first()->currency_id;
        $VendorDeliverySetting = VendorDeliverySetting::where(VendorDeliverySetting::vendorId, $request->vendor_id)->first();
        $deliverySetting = new \stdClass();
        $deliverySetting->delivery_setting = isset($VendorDeliverySetting)?(string)$VendorDeliverySetting->delivery_setting:"0";
        $deliverySetting->delivery_charges = isset($VendorDeliverySetting)?(string)$VendorDeliverySetting->delivery_charges:"0";

        return [
            "vendor_stripe_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::stripePaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_paypal_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::paypalPaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_razor_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::razorPaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_paystack_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::paystackPaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_cod_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::codPaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_flutterwave_enabled" => (string) VendorPayment::where(VendorPayment::paymentId, Constants::flutterwavePaymentId)->where(VendorPayment::vendorId, $request->vendor_id)->first()->status,
            "vendor_razor_key" => (string) VendorPaymentInfo::select('value')->where(VendorPaymentInfo::coreKeysId, Constants::razorKey)->where(VendorPaymentInfo::vendorId, $request->vendor_id)->first()->value,
            "vendor_stripe_publishable_key" => (string) VendorPaymentInfo::select('value')->where(VendorPaymentInfo::coreKeysId, Constants::stripePublishableKey)->where(VendorPaymentInfo::vendorId, $request->vendor_id)->first()->value,
            "vendor_paystack_key" => (string) VendorPaymentInfo::select('value')->where(VendorPaymentInfo::coreKeysId, Constants::paystackKey)->where(VendorPaymentInfo::vendorId, $request->vendor_id)->first()->value,
            "vendor_flutterwave_public_key" => (string) VendorPaymentInfo::select('value')->where(VendorPaymentInfo::coreKeysId, Constants::flutterwavePublicKey)->where(VendorPaymentInfo::vendorId, $request->vendor_id)->first()->value,
            "currency_id" => isset($currency_id)?(string)$currency_id:"",
            "vendor_delivery_setting" => $deliverySetting
        ];
    }
}
