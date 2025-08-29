<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\User;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use App\Http\Contracts\User\UserInfoServiceInterface;
use App\Http\Contracts\User\BlueMarkUserServiceInterface;
use Modules\Core\Http\Requests\User\StoreBlueMarkUserReqeust;

class BlueMarkUserApiController extends PsApiController
{
    public function __construct(protected BlueMarkUserServiceInterface $blueMarkUserService,
        protected UserInfoServiceInterface $userInfoService)
    {
        parent::__construct();
    }

    //verify user bluemark
    public function verifyBlueMark(StoreBlueMarkUserReqeust $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        Auth::loginUsingId($loginUserId);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try {
            $validateData = $request->validated();

            $userInfo = $this->userInfoService->get(id: null, relation: null, parentId: $request->input('user_id'), coreKeysId: Constants::usrIsVerifyBlueMark);

            $message = $this->getBlueMarkMessage($userInfo?->value);

            if (empty($userInfo) || $userInfo->value == null || $userInfo->value == Constants::blueMarkRejectStatus) {
                // save blue mark user
                $this->blueMarkUserService->save($validateData);

            }

            $data = [
                'msg' => __($message, [], $request->language_symbol),
                'statusCode' => Constants::createdStatusCode,
                'flag' => Constants::success,
            ];

            return responseMsgApi($data['msg'], $data['statusCode'], $data['flag']);
        } catch (\Exception $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    /////////////////////////////////////////////////////////////
    /// Private Functions
    /////////////////////////////////////////////////////////////
    private function getBlueMarkMessage($status)
    {
        switch ($status) {
            case 1:
                return 'blueMark__api_already_blue_mark';
                break;
            case 2:
                return 'blueMark__api_pending_blue_mark';
                break;
            default:
                return 'blueMark__api_blue_mark_success';
        }
    }
}
