<?php

namespace Modules\Core\Http\Services\Authorization;

use App\Config\Cache\RolePermissionCache;
use App\Http\Contracts\Authorization\RolePermissionServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Http\Facades\PsCache;

class RolePermissionService extends PsService implements RolePermissionServiceInterface
{
    public function __construct() {}

    public function save($rolePermissionData)
    {
        if (! is_object($rolePermissionData)) {
            return;
        }
        $rolePermissions = new RolePermission();
        $rolePermissions->role_id = $rolePermissionData->role_id;
        $rolePermissions->module_id = $rolePermissionData->module_id;
        $rolePermissions->permission_id = $rolePermissionData->permission_id;
        $rolePermissions->added_user_id = Auth::id();
        $rolePermissions->save();

        return $rolePermissionData;
    }

    public function deleteAll($ids)
    {
        RolePermission::destroy($ids);
    }

    public function getAll($roleIds = null, $moduleId = null, $pagPerPage = null, $noPagination = null, $roleId = null)
    {
        $param = [$roleIds, $moduleId, $pagPerPage, $noPagination, $roleId];

        return PsCache::remember([RolePermissionCache::BASE, $roleIds[0], $moduleId], RolePermissionCache::GET_ALL_EXPIRY, $param,
            function() use($roleIds, $moduleId, $pagPerPage, $noPagination, $roleId) {
                $rolePermissions = RolePermission::when($roleIds, function ($q, $roleIds) {
                    $q->whereIn(RolePermission::roleId, $roleIds);
                })
                    ->when($moduleId, function ($q, $moduleId) {
                        $q->where(RolePermission::moduleId, $moduleId);
                    })
                    ->when($roleId, function ($q, $roleId) {
                        $q->where(RolePermission::roleId, $roleId);
                    });
                if ($pagPerPage) {
                    return $rolePermissions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    return $rolePermissions->get();
                }
            });
    }
}
