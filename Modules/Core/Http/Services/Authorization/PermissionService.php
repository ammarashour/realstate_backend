<?php

namespace Modules\Core\Http\Services\Authorization;

use App\Config\ps_constant;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use App\Http\Contracts\Authorization\RolePermissionServiceInterface;
use App\Http\Contracts\Authorization\UserPermissionServiceInterface;
use App\Http\Contracts\Vendor\VendorRoleServiceInterface;
use App\Http\Contracts\Vendor\VendorUserPermissionServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorRole;
use Modules\Core\Entities\Vendor\VendorRolePermission;

class PermissionService extends PsService implements PermissionServiceInterface
{

    public function __construct(
        protected UserPermissionServiceInterface $userPermissionServiceInterface,
        protected RolePermissionServiceInterface $rolePermissionServiceInterface,
        protected VendorUserPermissionServiceInterface $vendorUserPermissionService,
        protected VendorRoleServiceInterface $vendorRoleService
    )
    {}

    public function checkingForPermissionWithModel($ability, $model, $routeName = null, $msg = null)
    {
        return $this->checkPermission($ability, $model, $routeName);
    }

    public function checkingForPermissionWithoutModel($moduleId, $permissionId, $loginUserId, $routeName = null, $msg = null)
    {
        return $this->checkingPermissionWithoutModel($moduleId, $permissionId, $msg, $routeName, $loginUserId);
    }

    public function checkingForVendorPermission($moduleId, $permissionId, $vendorId, $loginUserId, $routeName = null, $msg = null)
    {
        return $this->checkingVendorPermission($moduleId, $permissionId, $vendorId, $msg, $routeName, $loginUserId);
    }

    public function checkingForCreateAbilityWithModel($keyValueArr)
    {
        return $this->handleCreateAblilityWithModel($keyValueArr);
    }

    public function checkingForCreateAbilityWithoutModel($keyValueArr, $loginUserId)
    {
        return $this->handleCreateAblilityWithoutModel($keyValueArr, $loginUserId);
    }

    public function permissionControl($module_id, $permission_id, $loginUserId = null)
    {
        $loginUserId = $loginUserId ?? Auth::id();
        $loginUserRoles = $this->userPermissionServiceInterface->get($loginUserId);

        if(!$loginUserRoles){
            return false;
        }

        $roleIds = explode(',', $loginUserRoles->role_id);
        $userAccesses = $this->rolePermissionServiceInterface->getAll($roleIds, $module_id, null, Constants::yes);

        return $this->handlePermissionForModule($userAccesses, $permission_id);
    }

    public function vendorPermissionControl($module_id, $permission_id, $vendorId, $loginUserId = null)
    {
        $permission_id = strval($permission_id);
        $loginUserId = $loginUserId ?? Auth::id();

        $vendorRole = $this->vendorUserPermissionService->get($loginUserId);

        if (! $vendorRole) {
            return false;
        }

        $vendorRoleObj = json_decode($vendorRole->vendor_and_role);

        if (! isset($vendorRoleObj->$vendorId)) {
            return false;
        }

        $getRoleIds = explode(',', $vendorRoleObj->$vendorId);
        $roleIds = $this->vendorRoleService->getAll(
            roleIds: $getRoleIds,
            status: Constants::publish,
            noPagination: Constants::yes
        )->pluck(VendorRole::id)->toArray();

        if ($permission_id == strval(ps_constant::readPermission)) {
            /** @todo to refactor if LMP finish */
            $rowPermission = VendorRolePermission::whereIn('vendor_role_id', $roleIds)
                ->whereJsonContains('module_and_permission->' . $module_id, $permission_id)
                ->orWhereJsonContains('module_and_permission->' . $module_id, strval(ps_constant::createPermission))
                ->orWhereJsonContains('module_and_permission->' . $module_id, strval(ps_constant::updatePermission))
                ->orWhereJsonContains('module_and_permission->' . $module_id, strval(ps_constant::deletePermission))
                ->first();
        } else {
            $rowPermission = VendorRolePermission::whereIn('vendor_role_id', $roleIds)->whereJsonContains('module_and_permission->' . $module_id, $permission_id)->first();
        }

        if ($rowPermission) {
            return true;
        }

        return false;
    }

    public function authorizationWithoutModel($moduleId, $loginUserId)
    {
        return [
            "create" => $this->handlePermissionForSingleAbilityWithoutModel($moduleId, ps_constant::createPermission, $loginUserId),
            "update" => $this->handlePermissionForSingleAbilityWithoutModel($moduleId, ps_constant::updatePermission, $loginUserId),
            "delete" => $this->handlePermissionForSingleAbilityWithoutModel($moduleId, ps_constant::deletePermission, $loginUserId)
        ];
    }

    public function vendorAuthorizationWithoutModel($moduleId, $vendorId, $loginUserId = null)
    {
        $loginUserId = $loginUserId ?? Auth::id();

        return [
            'create' => $this->handleVendorPermissionForSingleAbilityWithoutModel($moduleId, ps_constant::createPermission, $vendorId, $loginUserId),
            'update' => $this->handleVendorPermissionForSingleAbilityWithoutModel($moduleId, ps_constant::updatePermission, $vendorId, $loginUserId),
            'delete' => $this->handleVendorPermissionForSingleAbilityWithoutModel($moduleId, ps_constant::deletePermission, $vendorId, $loginUserId),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------

    private function handleVendorPermissionForSingleAbilityWithoutModel($moduleId, $permissionId, $vendorId, $loginUserId)
    {
        return $this->vendorPermissionControl($moduleId, $permissionId, $vendorId) ? true : false;
    }

    private function handlePermissionForSingleAbilityWithoutModel($moduleId, $permissionId, $loginUserId)
    {
        return $this->permissionControl($moduleId, $permissionId, $loginUserId) ? true : false;
    }

    private function handleCreateAblilityWithModel($keyValueArr)
    {
        $dataArr = [];
        foreach($keyValueArr as $key => $value){
            $data = Auth::check() ? auth()->user()->can($value) : '';
            $dataArr[$key] = $data;
        }
        return $dataArr;
    }

    private function handleCreateAblilityWithoutModel($keyValueArr, $loginUserId)
    {
        $dataArr = [];
        foreach($keyValueArr as $key => $value){
            $data = $this->permissionControl($value, ps_constant::createPermission, $loginUserId) ? true : false;
            $dataArr[$key] = $data;
        }

        return $dataArr;
    }

    private function checkingPermissionWithoutModel($moduleId, $permissionId, $msg, $routeName, $loginUserId)
    {
        if(!$this->permissionControl($moduleId, $permissionId, $loginUserId)){
            return redirectView($routeName, $msg, "danger");
        }
    }

    private function checkingVendorPermission($moduleId, $permissionId, $vendorId, $msg, $routeName, $loginUserId)
    {
        if(!$this->vendorPermissionControl($moduleId, $permissionId, $vendorId, $loginUserId)){
            return redirectView($routeName, $msg, "danger");
        }
    }

    private function handlePermissionForModule($userAccesses, $permission_id)
    {
        foreach ($userAccesses as $userAccess) {

            $permission = $userAccess->permission_id;
            $permissionIds = explode(',', $permission);

            if($this->handleForReadPermission($permissionIds, $permission_id) && $permission_id == ps_constant::readPermission){
                return true;
            }

            if($this->handleForOtherPermission($permissionIds, $permission_id)){
                return true;
            }

        }
    }

    private function handleForReadPermission($permissionIds, $permission_id)
    {
        if ((int)$permission_id !== (int)ps_constant::readPermission) {
            return false;
        }

        $validReadPermissions = [
            (int)ps_constant::readPermission,
            (int)ps_constant::createPermission,
            (int)ps_constant::updatePermission,
            (int)ps_constant::deletePermission,
        ];

        $intersectingPermissions = array_intersect($permissionIds, $validReadPermissions);

        return !empty($intersectingPermissions);
    }

    private function handleForOtherPermission($permissionIds, $permission_id)
    {
        return in_array($permission_id, array_map('intval', $permissionIds), true);
    }

}
