<?php
namespace Modules\Core\Http\Services;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\BeSettingCache;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Transformers\Api\App\V1_0\AppInfo\AppInfoApiResource;

use function PHPUnit\Framework\returnSelf;

class AppInfoService extends PsService
{
    protected $deviceTokenParaApi, $loginUserIdParaApi, $userAccessApiTokenService, $forbiddenStatusCode;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService,protected MobileSettingServiceInterface $mobileSettingService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;

        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;
    }

    // for api
    public function indexFromApi(?Request $request = null)
    {
        $userAccessApiToken = $request?->cookie('user_access_api_token') ?? $request?->get('user_access_api_token');

        $fetchedUserAcessApiToken = $userAccessApiToken
            ? ($this->userAccessApiTokenService->getUserAccessApiToken(deviceToken: $userAccessApiToken)?->count() > 0 ? 1 : 0)
            : 0;
        $data = PsCache::remember([AppInfoCache::BASE], AppInfoCache::GET_EXPIRY, null,
                function(){
                    return new AppInfoApiResource([]);
                });
        return responseDataApi(array_merge($data->toArray(request()), ['is_user_token_valid' => $fetchedUserAcessApiToken]));

    }

    public function forVendor()
    {
        $appInfo = new AppInfoApiResource([]);
        return $appInfo;
    }

}
