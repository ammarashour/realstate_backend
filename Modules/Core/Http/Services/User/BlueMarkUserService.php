<?php

namespace Modules\Core\Http\Services\User;

use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Contracts\User\BlueMarkUserServiceInterface;
use App\Http\Contracts\User\UserInfoServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\BlueMarkUser;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\UserInfo;

class BlueMarkUserService extends PsService implements BlueMarkUserServiceInterface
{
    public function __construct(protected UserInfoServiceInterface $userInfoService,
        protected UserServiceInterface $userService,
        protected PushNotificationTokenServiceInterface $pushNotificationTokenService,
        protected FirebaseCloudMessagingServiceInterface $firebaseCloudMessagingService) {}

    public function save($userInfoData)
    {
        DB::beginTransaction();
        try {
            $userId = $userInfoData['user_id'];
            $conds = $this->prepareBlueMarkUser($userId);
            $blueMarkUser = $this->get(id: null, conds: $conds);

            if (empty($blueMarkUser)) {
                //save blue mark user
                $blueMarkUserData = $this->prepareBlueMarkUser($userId);
                $this->saveBlueMarkUser($blueMarkUserData);
            }

            //save user info
            $customFieldValues = $this->prepareCustomFieldData(Constants::blueMarkPendingStatus, $userInfoData['note']);
            $this->userInfoService->update($userId, $customFieldValues);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $userInfoData)
    {
        DB::beginTransaction();
        try {
            $customFieldValues = [
                Constants::usrIsVerifyBlueMark => $userInfoData['value'],
            ];

            $this->userInfoService->update($id, $customFieldValues);

            // send noti
            $this->sendBlueMarkNoti($id, $userInfoData['value']);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $conds = $this->prepareBlueMarkUser($id);
            $blueMarkUser = $this->get(null, $conds);
            if ($blueMarkUser) {
                $this->deleteBlueMarkUser($blueMarkUser->id);
            }

            $coreKeysIds = [Constants::usrIsVerifyBlueMark, Constants::usrBlueMarkNote];
            $customFieldValues = $this->userInfoService->getAll(
                coreKeysIds: $coreKeysIds,
                parentId: $id,
                relation: null,
                noPagination: true,
                pagPerPage: null);

            $this->userInfoService->deleteAll($customFieldValues);

            $user = $this->userService->get($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $user->name]),
                'flag' => Constants::success,
            ];

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $conds = null)
    {
        return BlueMarkUser::when($id, function ($query, $id) {
            $query->where(BlueMarkUser::id, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
    }

    public function getAll($conds = null, $noPagination = null, $pagPerPage = null)
    {
        $users = User::select(User::tableName.'.*', UserInfo::t(UserInfo::updatedDate).' as bluemark_updated_at')
            ->join(UserInfo::tableName, User::t(User::id), '=', UserInfo::t(UserInfo::userId))
            ->where(UserInfo::t(UserInfo::coreKeysId), Constants::usrIsVerifyBlueMark)
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            });

        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users->get();
        }

        return $users;
    }

    ///////////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////////

    ///----------------------------------------------------------------
    /// Data Preparation
    ///----------------------------------------------------------------
    private function prepareBlueMarkUser($id)
    {
        return [
            'user_id' => $id,
        ];
    }

    private function prepareCustomFieldData($status, $note)
    {
        return [
            Constants::usrIsVerifyBlueMark => $status,
            Constants::usrBlueMarkNote => $note,
        ];
    }

    private function prepareTokenConds($userId)
    {
        return [
            'user_id' => $userId,
        ];
    }

    private function prepareFcmData($message)
    {
        return [
            'message' => __($message),
            'flag' => Constants::verifyBlueMarkNotiFlag,
        ];
    }

    ///----------------------------------------------------------------
    /// Database
    ///----------------------------------------------------------------
    private function saveBlueMarkUser($blueMarkUserData)
    {
        $blueMarkUser = new BlueMarkUser;
        $blueMarkUser->fill($blueMarkUserData);
        $blueMarkUser->added_user_id = Auth::id();
        $blueMarkUser->save();

        return $blueMarkUser;
    }

    private function deleteBlueMarkUser($id)
    {
        $blueMarkUser = $this->get($id);
        $blueMarkUser->delete();
    }

    private function searching($query, $conds)
    {
        $query->when(isset($conds['searchterm']) && $conds['searchterm'] != '', function ($query) use ($conds) {
            $query->where(function ($query) use ($conds) {
                $query->where(User::t(User::name), 'like', '%'.$conds['searchterm'].'%')
                    ->orWhere(User::t(User::email), 'like', '%'.$conds['searchterm'].'%');
            });
        })
            ->when(isset($conds['bluemark_status']), function ($query) use ($conds) {
                $query->where(UserInfo::t(UserInfo::value), $conds['bluemark_status']);
            })
            ->when(isset($conds['bluemark_updated_at']), function ($query) use ($conds) {
                $query->whereBetween(UserInfo::t(UserInfo::updatedDate), $conds['bluemark_updated_at']);
            })
            ->when(isset($conds['order_by']) && isset($conds['order_type']), function ($query) use ($conds) {
                $query->when($conds['order_by'] == Constants::usrIsVerifyBlueMark, function ($query) use ($conds) {
                    $query->orderBy(UserInfo::t(UserInfo::value), $conds['order_type']);
                })->when($conds['order_by'] != Constants::usrIsVerifyBlueMark, function ($query) use ($conds) {
                    $query->orderBy($conds['order_by'], $conds['order_type']);
                });
            });

        return $query;
    }

    ///----------------------------------------------------------------
    /// Other
    ///----------------------------------------------------------------
    private function sendBlueMarkNoti($userId, $status)
    {
        $funArr = [
            '',
            function ($userId) {
                $this->sendNoti($userId, 'core__be_verify_blue_mark_noti_approve');
            },
            function () {},
            function ($userId) {
                $this->sendNoti($userId, 'core__be_verify_blue_mark_noti_reject');
            },
        ];

        $funArr[$status]($userId);
    }

    private function sendNoti($userId, $message)
    {
        // send noti to blue mark user
        $user = $this->userService->get($userId);

        $tokenConds = $this->prepareTokenConds($user->id);
        $notiTokens = $this->pushNotificationTokenService->getAll(conds: $tokenConds, noPagination: Constants::yes);

        $deviceIds = $notiTokens->pluck('device_token')->toArray();
        $platformNames = $notiTokens->pluck('platform_name')->toArray();

        $data = $this->prepareFcmData($message);

        foreach ($deviceIds as $deviceId) {
            $this->firebaseCloudMessagingService->sendAndroidFcm($deviceId, $data, $platformNames);
        }

        // send mail to blue mark user
        sendMail($user->email, $user->name, __('core__be_blue_mark'), null, $message);
    }
}
