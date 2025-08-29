<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Authorization;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Http\Requests\Authorization\RegisterPushNotiTokenRequest;
use Modules\Core\Http\Requests\Authorization\UnRegisterPushNotiTokenRequest;

class PushNotificationTokenApiController extends PsApiController
{

    public function __construct(
        protected Translator $translator,
        protected PushNotificationTokenServiceInterface $pushNotificationTokenService
    )
    {
        parent::__construct();
    }

    public function registerNoti(RegisterPushNotiTokenRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->pushNotificationTokenService->registerFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function unregisterNoti(UnRegisterPushNotiTokenRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

       return $this->pushNotificationTokenService->unregisterFromApi($validatedData, $langSymbol, $loginUserId);

    }
}
