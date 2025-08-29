<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Modules\Core\Entities\UserAccessApiToken;

class UserAccessApiTokenService extends PsService
{
    protected $userAccessApiTokenDeviceTokenCol, $userAccessApiTokenUserIdCol, $userAccessApiTokenDeviceIdCol;
    public function __construct()
    {
        $this->userAccessApiTokenUserIdCol = UserAccessApiToken::userId;
        $this->userAccessApiTokenDeviceIdCol = UserAccessApiToken::deviceId;
        $this->userAccessApiTokenDeviceTokenCol = UserAccessApiToken::deviceToken;
    }

    public function getUserAccessApiToken($userId = null, $deviceToken = null, $deviceId = null){
        $userAccessApiToken = UserAccessApiToken::when($userId, function ($q, $userId){
                                $q->where($this->userAccessApiTokenUserIdCol, $userId);
                            })
                            ->when($deviceToken, function ($q, $deviceToken){
                                $q->where($this->userAccessApiTokenDeviceTokenCol, $deviceToken);
                            })
                            ->when($deviceId, function ($q, $deviceId){
                                $q->where($this->userAccessApiTokenDeviceIdCol, $deviceId);
                            })
                            ->first();
        return $userAccessApiToken;
    }

    public function getUserAccessApiTokens($userId = null){
        $userAccessApiTokens = UserAccessApiToken::when($userId, function ($q, $userId){
                                $q->whereIn($this->userAccessApiTokenUserIdCol, $userId);
                            })
                            ->get();
        return $userAccessApiTokens;
    }

    public function storeOrUpdate($request, $userId, $deviceId){
        $userAccessApiToken = $this->getUserAccessApiToken($userId, null, $deviceId);
        $headerToken = ps_constant::deviceTokenKeyFromApi;
        if(empty($request->header($headerToken))){
            return false;
        }
        if (!empty($userAccessApiToken)){
            $userAccessApiToken->device_info = $request->device_info;
            $userAccessApiToken->device_id = $deviceId;
            $userAccessApiToken->device_token = $request->header($headerToken);
            $userAccessApiToken->user_id = $userId;
            $userAccessApiToken->update();
        } else {
            // save in user_access_api_tokens table
            $userAccessApiToken = new UserAccessApiToken();
            $userAccessApiToken->device_info = $request->device_info;
            $userAccessApiToken->device_id = $deviceId;
            $userAccessApiToken->device_token = $request->header($headerToken);
            $userAccessApiToken->user_id = $userId;
            $userAccessApiToken->save();
        }
        return true;
    }

}
