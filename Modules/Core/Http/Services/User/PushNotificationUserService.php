<?php

namespace Modules\Core\Http\Services\User;

use App\Http\Contracts\User\PushNotificationUserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\User\PushNotificationUser;

class PushNotificationUserService extends PsService implements PushNotificationUserServiceInterface
{
    public function __construct() {}

    public function save($pushNotificationUserData, $loginUserId)
    {
        DB::beginTransaction();
        try {
            $noti = new PushNotificationUser();
            $noti->fill($pushNotificationUserData);
            $noti->added_user_id = $loginUserId;
            $noti->save();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id = null, $relation = null, $conds = null)
    {
        return PushNotificationUser::when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds){
                $q->where($conds);
            })
            ->when($id, function ($q, $id) {
                $q->where(PushNotificationUser::id, $id);
            })->first();
    }

    public function getAll($userId = null, $isSoftDel = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $pushNotificationUsers = PushNotificationUser::when($userId, function ($q, $userId){
                                                $q->where(PushNotificationUser::user_id, $userId);
                                            })
                                            ->when($conds, function ($q, $conds){
                                                $q->where($conds);
                                            })
                                            ->when($isSoftDel !== null,  function ($query) use ($isSoftDel){
                                                $query->onlyTrashed();
                                            });


        if ($pagPerPage) {
            $pushNotificationUsers = $pushNotificationUsers->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else{
            $pushNotificationUsers = $pushNotificationUsers->get();
        }

        return $pushNotificationUsers;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

}
