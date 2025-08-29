<?php

namespace Modules\Core\Http\Services\Authorization;

use App\Config\Cache\UserPermissionCache;
use App\Http\Contracts\Authorization\UserPermissionServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Http\Facades\PsCache;

class UserPermissionService extends PsService implements UserPermissionServiceInterface
{
    public function __construct() {}

    public function save($userPermissionData)
    {
        if (! is_object($userPermissionData)) {
            return;
        }
        $userPermission = new UserPermission();
        $userPermission->user_id = $userPermissionData->user_id;
        $userPermission->role_id = $userPermissionData->role_id;
        $userPermission->added_user_id = Auth::id();
        $userPermission->save();

        return $userPermission;
    }

    public function update($id = null, $userId = null, $userPermissionData)
    {
        if (! is_object($userPermissionData)) {
            return;
        }

        $userPermission = $this->get($userId);
        $userPermission->user_id = $userPermissionData->user_id;
        $userPermission->role_id = $userPermissionData->role_id;
        $userPermission->updated_user_id = Auth::id();
        $userPermission->update();

        PsCache::clear(UserPermissionCache::BASE, $userId);

        return $userPermission;
    }

    public function delete($id = null, $userId = null)
    {
        $userPermission = $this->get($userId);
        $userPermission->delete();
    }

    public function get($userId = null, $roleId = null)
    {
        $param = [$userId, $roleId];

        return PsCache::remember([UserPermissionCache::BASE, $userId], UserPermissionCache::GET_EXPIRY, $param,
            function() use($userId, $roleId){
                return UserPermission::when($userId, function ($q, $userId) {
                            $q->where(UserPermission::userId, $userId);
                        })
                            ->when($roleId, function ($q, $roleId) {
                                $q->where(UserPermission::roleId, $roleId);
                            })
                            ->first();
            });
    }

    public function getAll($userId = null, $roleId = null, $pagPerPage = null, $noPagination = null)
    {
        $userPermissions = UserPermission::when($userId, function ($q, $userId) {
            $q->where(UserPermission::userId, $userId);
        })
            ->when($roleId, function ($q, $roleId) {
                $q->where(UserPermission::roleId, $roleId);
            });
        if ($pagPerPage) {
            return $userPermissions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $userPermissions->get();
        }
    }
}
