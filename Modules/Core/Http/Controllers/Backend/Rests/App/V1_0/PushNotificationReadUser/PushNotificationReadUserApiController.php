<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\PushNotificationReadUser;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use App\Http\Contracts\User\PushNotificationReadUserServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\User\IsReadPushNotificationReadUserRequest;
use Modules\Core\Http\Requests\User\IsSoftDeletePushNotificationReadUserRequest;
use Modules\Core\Http\Requests\User\IsUnReadPushNotificationReadUserRequest;
use Modules\Core\Transformers\Api\App\V1_0\Notification\PushNotificationMessageApiResource;

class PushNotificationReadUserApiController extends PsApiController
{
    public function __construct(
        protected Translator $translator,
        protected PushNotificationReadUserServiceInterface $pushNotificationReadUserService
    ) {
        parent::__construct();
    }

    public function isReadNoti(IsReadPushNotificationReadUserRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        $checkUserByLoginUser = checkUserByLoginUser($validatedData['user_id'], $loginUserId);

        if (! $checkUserByLoginUser) {
            throw new PsApiException(__('core__api_no_permission', [], $langSymbol), Constants::forbiddenStatusCode);
        }

        $response = $this->pushNotificationReadUserService->isReadFromApi($validatedData, $loginUserId, $headerToken, $langSymbol);

        $sendnoti = new PushNotificationMessageApiResource($response);

        return responseDataApi($sendnoti);

    }

    public function isUnreadNoti(IsUnReadPushNotificationReadUserRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        $response = $this->pushNotificationReadUserService->isUnreadFromApi($validatedData, $loginUserId, $headerToken, $langSymbol);

        $sendnoti = new PushNotificationMessageApiResource($response);

        return responseDataApi($sendnoti);
    }

    public function destroy(IsSoftDeletePushNotificationReadUserRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->pushNotificationReadUserService->destroyFromApi($validatedData, $loginUserId, $headerToken, $langSymbol);
    }
}
