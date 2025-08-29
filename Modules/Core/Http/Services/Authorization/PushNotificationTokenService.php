<?php

namespace Modules\Core\Http\Services\Authorization;

use App\Exceptions\PsApiException;
use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Authorization\PushNotificationToken;
use Throwable;

class PushNotificationTokenService extends PsService implements PushNotificationTokenServiceInterface
{
    public function __construct() {}

    public function save($pushNotificationTokenData, $loginUserId)
    {
        DB::beginTransaction();

        try {
            if(!isset($pushNotificationTokenData['device_token']) || $pushNotificationTokenData['device_token'] == '' || $pushNotificationTokenData['device_token'] == 'errorToken') {
                return;
            }
            $this->savePushNotificationToken($pushNotificationTokenData, $loginUserId);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $pushNotificationTokenData, $loginUserId)
    {
        DB::beginTransaction();

        try {

            $this->updatePushNotificationToken($id, $pushNotificationTokenData, $loginUserId);
            DB::commit();
        } catch (\Throwable$e) {
            DB::rollBack();
            throw new PsApiException($e->getMessage(), Constants::notFoundStatusCode);
        }
    }

    public function delete($id = null, $token = null)
    {
        try {
            $deviceToken = $this->deletePushNotificationToken($id, $token);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $deviceToken]),
                'flag' => Constants::success,
            ];
        } catch (Throwable $e) {
            throw $e;
        }

    }

    public function get($id = null, $conds = null, $relation = null, $deviceToken = null)
    {
        $pushNotificationToken = PushNotificationToken::when($id, function ($q, $id) {
            $q->where(PushNotificationToken::id, $id);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($deviceToken, function ($q, $deviceToken) {
                $q->where(PushNotificationToken::device_token, $deviceToken);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();

        return $pushNotificationToken;
    }

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null, $pagPerPage = null, $noPagination = null, $deviceToken = null)
    {
        PushNotificationToken::where('added_date', '<=', Carbon::now()->subDays(1))
            ->where('platform_name', 'frontend')
            ->delete();

        $pushNotificatoinTokens = PushNotificationToken::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($deviceToken, function ($q, $deviceToken) {
                $q->where(PushNotificationToken::device_token, $deviceToken);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            });

        if ($pagPerPage) {
            return $pushNotificatoinTokens->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $pushNotificatoinTokens->get();
        }
    }

    public function storeOrUpdateNotiToken($pushNotificationTokenData, $loginUserId)
    {
        try{
            $deviceToken = $pushNotificationTokenData['device_token'] ?? 'errorToken';
            $pushNotificationTokenData['user_id'] = $pushNotificationTokenData['user_id'] ?? $loginUserId;
            $platformName = $pushNotificationTokenData['platform_name'] ?? 'frontend';

            if ($deviceToken == 'errorToken') {
                return false;
            }

            $noti_count = $this->getAll(deviceToken: $deviceToken, noPagination: Constants::yes)->count();

            if ($noti_count == 1) {

                $pushNotificationToken = $this->get(deviceToken: $deviceToken);

                $this->update($pushNotificationToken->id, $pushNotificationTokenData, $loginUserId);

            } else {
                $pushNotificationToken = $this->get(deviceToken: $deviceToken);
                if(!empty($pushNotificationToken)){
                    $this->delete($pushNotificationToken->id);
                }
                $this->save($pushNotificationTokenData, $loginUserId);
            }

            return true;

        } catch(Throwable $e) {
            throw $e;
        }


    }

    public function registerFromApi($pushNotificationTokenData, $langSymbol, $loginUserId)
    {

        $conds['device_token'] = $pushNotificationTokenData['device_token'];
        $conds['user_id'] = $pushNotificationTokenData['user_id'];

        $pushNotificationTokenCount = $this->getAll(conds: $conds, noPagination: Constants::yes)->count();

        if ($pushNotificationTokenCount != 0) {
            $message = __('noti__api_already_registered', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        try {

            $this->save($pushNotificationTokenData, $loginUserId);
            $message = __('noti__api_register_success', [], $langSymbol);

            return responseMsgApi(
                $message,
                Constants::createdStatusCode,
                Constants::success
            );

        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }

    }

    public function unregisterFromApi($pushNotificationTokenData, $langSymbol, $loginUserId)
    {

        $conds['device_token'] = $pushNotificationTokenData['device_token'];
        $conds['user_id'] = $pushNotificationTokenData['user_id'];

        $pushNotificationTokenCount = $this->getAll(conds: $conds, noPagination: Constants::yes)->count();

        if ($pushNotificationTokenCount == 0) {
            $message = __('noti__api_token_not_exit', [], $langSymbol);
            throw new PsApiException($message, Constants::badRequestStatusCode);
        }

        try {

            $pushNotificationToken = $this->get(conds: $conds);
            $this->delete($pushNotificationToken->id);
            $message = __('noti__api_unregister_success', [], $langSymbol);

            return responseMsgApi(
                $message,
                Constants::okStatusCode,
                Constants::success
            );

        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function savePushNotificationToken($pushNotificationTokenData, $loginUserId)
    {
        $pushNotificationToken = new PushNotificationToken();
        $pushNotificationToken->fill($pushNotificationTokenData);
        $pushNotificationToken->added_user_id = $loginUserId;
        $pushNotificationToken->save();

        return $pushNotificationToken;
    }

    private function updatePushNotificationToken($id, $pushNotificationTokenData, $loginUserId)
    {
        $pushNotificationToken = $this->get($id);
        $pushNotificationToken->fill($pushNotificationTokenData);
        $pushNotificationToken->added_user_id = $loginUserId;
        $pushNotificationToken->update();

        return $pushNotificationToken;
    }

    private function deletePushNotificationToken($id = null, $token = null)
    {
        $pushNotificationToken = $id ? $this->get($id) : $this->get(deviceToken: $token);
        $deviceToken = $pushNotificationToken->device_token;
        $pushNotificationToken->delete();

        return $deviceToken;
    }
}
