<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Financial;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Modules\Core\Constants\Constants;
use Illuminate\Routing\Controller;
use App\Http\Controllers\PsApiController;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Transformers\Api\App\V1_0\Financial\PackageInAppPurchaseSettingApiResource;
use Modules\Payment\Transformers\Api\App\V1_0\Payment\PackageWithPurchasedCountApiResource;
use App\Http\Contracts\Financial\PackageInAppPurchaseServiceInterface;

class PackageInAppPurchaseSettingApiController extends PsApiController
{

    public function __construct(
        protected PackageInAppPurchaseServiceInterface $packageInAppPurchaseSettingService,
        protected PaymentSettingService $paymentSettingService,
        protected MobileSettingServiceInterface $mobileSettingService
    )
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $indexData = $this->prepareDataForIndex($request);
        $package_in_app_purchases = PackageInAppPurchaseSettingApiResource::collection($this->paymentSettingService->getPaymentInfos($indexData['packageInAppPurchaseApiRelations'], $indexData['limit'], $indexData['offset'], $indexData['conds'], true, null, $indexData['attributes']));

        return $package_in_app_purchases;
    }

    public function packagePurchasedCount(Request $request)
    {
        $purchasedCountData = $this->prepareDataForPurchasedCount($request);

        $package_purchased_count = PackageWithPurchasedCountApiResource::collection($this->paymentSettingService->getPaymentInfos($purchasedCountData['packageInAppPurchaseApiRelations'], $purchasedCountData['limit'], $purchasedCountData['offset'], $purchasedCountData['conds'], true, null, $purchasedCountData['attributes']));
        return $package_purchased_count;
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

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
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

        $conds = [
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'status' => 1,

        ];

        $attributes = [
            Constants::pmtAttrPackageIapTypeCol,
            Constants::pmtAttrPackageIapCountCol,
            Constants::pmtAttrPackageIapPriceCol,
            Constants::pmtAttrPackageIapStatusCol,
            Constants::pmtAttrPackageIapCurrencyCol
        ];
        return [
            'limit' => $limit,
            'offset' => $offset,
            'packageInAppPurchaseApiRelations' => $packageInAppPurchaseApiRelations,
            'attributes' => $attributes,
            'conds' => $conds,
        ];
    }

    private function prepareDataForPurchasedCount($request)
    {
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);

        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $packageInAppPurchaseApiRelations = ['payment', 'purchased_count', 'payment_info'];

        $conds = [
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'status' => 1,
        ];

        $attributes = [
            Constants::pmtAttrPackageIapTypeCol,
            Constants::pmtAttrPackageIapCountCol,
            Constants::pmtAttrPackageIapPriceCol,
            Constants::pmtAttrPackageIapStatusCol,
            Constants::pmtAttrPackageIapCurrencyCol
        ];
        return [
            'limit' => $limit,
            'offset' => $offset,
            'packageInAppPurchaseApiRelations' => $packageInAppPurchaseApiRelations,
            'attributes' => $attributes,
            'conds' => $conds,
        ];
    }
}
