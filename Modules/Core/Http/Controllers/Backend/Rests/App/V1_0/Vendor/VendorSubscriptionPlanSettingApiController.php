<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Vendor;

use App\Config\ps_constant;
use App\Http\Controllers\PsApiController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\Vendor\VendorSubscriptionPlanSettingService;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorSubscriptionPlanSettingApiResource;

class VendorSubscriptionPlanSettingApiController extends PsApiController
{
    public function __construct(protected VendorSubscriptionPlanSettingService $vendorSubscriptionPlanSettingService,
        protected PaymentSettingService $paymentSettingService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $vendor_subscription_plans = $this->prepareDataForIndex($request);

        return $vendor_subscription_plans;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareDataForIndex($request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $packageInAppPurchaseApiRelations = ['payment', 'core_key', 'payment_info'];
        $conds['payment_id'] = Constants::vendorSubscriptionPlanPaymentId;
        $conds['status'] = 1;
        $attributes = [
            Constants::pmtAttrVendorSpDurationCol,
            Constants::pmtAttrVendorSpSalePriceCol,
            Constants::pmtAttrVendorSpDiscountPriceCol,
            Constants::pmtAttrVendorSpCurrencyCol,
            Constants::pmtAttrVendorSpIsMostPopularPlanCol,
            Constants::pmtAttrVendorSpStatusCol,
        ];

        return VendorSubscriptionPlanSettingApiResource::collection($this->paymentSettingService->getPaymentInfos($packageInAppPurchaseApiRelations, $limit, $offset, $conds, true, null, $attributes));

    }
}
