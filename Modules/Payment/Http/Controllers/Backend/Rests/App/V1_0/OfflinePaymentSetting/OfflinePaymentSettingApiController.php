<?php

namespace Modules\Payment\Http\Controllers\Backend\Rests\App\V1_0\OfflinePaymentSetting;

use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Support\Renderable;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;
use App\Http\Contracts\Financial\OfflinePaymentSettingServiceInterface;
use Modules\Payment\Transformers\Api\App\V1_0\Payment\OfflinePaymentSettingApiResource;

class OfflinePaymentSettingApiController extends PsApiController
{
    protected $offlinePaymentApiRelations;
    public function __construct(
        protected OfflinePaymentSettingServiceInterface $offlinePaymentSettingService,
        protected PaymentInfoServiceInterface $paymentInfoService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
        $this->offlinePaymentApiRelations = [];
    }

    public function index(Request $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $attributes = [Constants::pmtAttrOfflinePaymentStatusCol];

        $data = OfflinePaymentSettingApiResource::collection($this->paymentInfoService->getAll($this->offlinePaymentApiRelations, $limit, $offset, $conds, true, null, $attributes));

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
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

    private function getFilterConditions($request)
    {
        return [
            'payment_id' => Constants::offlinePaymentId,
            'status' => Constants::publish,
        ];
    }
}
