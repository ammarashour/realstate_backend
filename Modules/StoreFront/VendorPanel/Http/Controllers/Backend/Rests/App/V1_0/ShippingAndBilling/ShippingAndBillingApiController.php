<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Rests\App\V1_0\ShippingAndBilling;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\ShippingAndBillingService;

class ShippingAndBillingApiController extends Controller
{
    protected $shippingAndBillingService, $translator;

    public function __construct(Translator $translator, ShippingAndBillingService $shippingAndBillingService)
    {
        $this->shippingAndBillingService = $shippingAndBillingService;
        $this->translator = $translator;
    }

    public function storeShippingAndBillingInfo(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:psx_vendors,id',
            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_email' => 'required|email',
            'shipping_phone_no' => 'required',
            'shipping_address' => 'required',
            'shipping_country' => 'required',
            'shipping_state' => 'required',
            'shipping_city' => 'required',
            'shipping_postal_code' => 'required',
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_phone_no' => 'required',
            'billing_address' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'billing_city' => 'required',
            'billing_postal_code' => 'required',
            'order_summary' => 'required|array',
            'order_summary.*.item_id' => 'required|exists:psx_items,id',
            'order_summary.*.quantity' => 'required',
            'order_summary.*.original_price' => 'required',
            'order_summary.*.sale_price' => 'required',
            'order_summary.*.sub_total' => 'required',
            'order_summary.*.discount_price' => 'required',
            'total_price' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        $ShippingAndbillingInfo = $this->shippingAndBillingService->storeShippingAndBillingInfoFromApi($request);
        return $ShippingAndbillingInfo;
    }
}
