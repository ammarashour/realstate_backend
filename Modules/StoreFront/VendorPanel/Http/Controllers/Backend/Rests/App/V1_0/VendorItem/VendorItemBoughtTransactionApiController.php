<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Rests\App\V1_0\VendorItem;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\VendorItemBoughtTransactionService;

class VendorItemBoughtTransactionApiController extends Controller
{
    protected $vendorItemBoughtTransactionService, $translator;

    public function __construct(Translator $translator, VendorItemBoughtTransactionService $vendorItemBoughtTransactionService)
    {
        $this->vendorItemBoughtTransactionService = $vendorItemBoughtTransactionService;
        $this->translator = $translator;
    }

    public function store(Request $request)
    {

        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required',
            'payment_amount' => 'required',
            'order_id' => 'required|exists:psx_orders,id',
            'vendor_id' => 'required|exists:psx_vendors,id',
            'currency_id' => 'required|exists:psx_currencies,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        // $package = $this->packageBoughtTransactionService->getPaymentInfoByPackageId($request->package_id);
        // if($package->payment_id != $this->packageInAppPurchasePaymentId){
        //     return responseMsgApi('package__pkg_invalid', $this->badRequestStatusCode);
        // }
        $vendorItemBought = $this->vendorItemBoughtTransactionService->storeFromApi($request);
        return $vendorItemBought;
    }

    public function vendorPaypalNonceGenegrate(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:psx_vendors,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end
        return $this->vendorItemBoughtTransactionService->vendorPaypalNonceGenegrate($request);
    }
}
