<?php

namespace App\Http\Controllers;

use App\Exceptions\PsApiException;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;

class PsApiController extends Controller
{
    protected $userAccessApiTokenService;
    public function __construct()
    {
        $this->userAccessApiTokenService = app(UserAccessApiTokenService::class);
    }

    public function handleNoDataResponse($offset, $data)
    {
        assert($this->userAccessApiTokenService !== null, "Child class must call parent constructor.");
        
        if(count($data) > 0){
            return $data;
        }

        if ($offset > 0) {
            // no paginate data
            return responseDataApi([]);
        }

        // no data db
        return responseMsgApi(__('core__no_data'), Constants::noContentStatusCode, Constants::successStatus);

    }

    public function checkApiPermission($loginUserId, $headerToken, $langSymbol)
    {
        assert($this->userAccessApiTokenService !== null, "Child class must call parent constructor.");

        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($loginUserId, $headerToken);

        if (empty($userAccessApiToken) || empty($headerToken)) {
            $msg = __('core__api_no_permission', [], $langSymbol);
            throw new PsApiException($msg, Constants::forbiddenStatusCode);
        }
    }

    public function checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $addedUserId, $msg = "core__api_update_no_permission")
    {
        assert($this->userAccessApiTokenService !== null, "Child class must call parent constructor.");

        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($loginUserId, $headerToken);

        if (empty($userAccessApiToken) || empty($headerToken)) {
            $message = __($msg, [], $langSymbol);
            throw new PsApiException($message, Constants::forbiddenStatusCode);
        }
        if((int)$loginUserId !== (int)$addedUserId){
            $message = __($msg, [], $langSymbol);
            throw new PsApiException($message, Constants::forbiddenStatusCode);
        }
    }
}
