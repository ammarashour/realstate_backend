<?php
namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Vendor\VendorConfigInfoApiResource;

/**
 * @deprecated
 */

class VendorInfoService extends PsService
{

    protected $userAccessApiTokenService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
    }

    public function getVendorInfoFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)){
            $msg = __('shipping_and_billing__api_store_info_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $vendorId = $request->vendor_id;
        
        $data = new VendorConfigInfoApiResource($vendorId);
        return responseDataApi($data);

    }
}
