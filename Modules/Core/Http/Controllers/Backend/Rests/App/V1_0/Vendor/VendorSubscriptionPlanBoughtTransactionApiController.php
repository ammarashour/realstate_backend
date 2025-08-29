<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Vendor;

use Modules\Core\Constants\Constants;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use App\Http\Contracts\Vendor\VendorSubscriptionPlanBoughtTransactionServiceInterface;
use App\Http\Controllers\PsApiController;
use Modules\Core\Http\Requests\Vendor\StoreVendorSubscriptionPlanBoughtRequest;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;


class VendorSubscriptionPlanBoughtTransactionApiController extends PsApiController
{

    public function __construct(protected Translator $translator, 
    protected VendorSubscriptionPlanBoughtTransactionServiceInterface $vendorSubscriptionPlanBoughtTransactionService,
    protected PaymentInfoServiceInterface $paymentInfoService)
    {
        parent::__construct();
    }

    public function store(StoreVendorSubscriptionPlanBoughtRequest $request)
    {
        $validateData = $request->validated();
        $package = $this->paymentInfoService->get($validateData['subscription_plan_id']);
        if($package['payment_id'] != Constants::vendorSubscriptionPlanPaymentId){
            return responseMsgApi('package__pkg_invalid', Constants::badRequestStatusCode);
        }
        $packages = $this->vendorSubscriptionPlanBoughtTransactionService->storeFromApi($validateData);
        return $packages;
    }
}
