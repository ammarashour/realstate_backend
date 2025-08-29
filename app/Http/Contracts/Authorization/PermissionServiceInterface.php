<?php

namespace App\Http\Contracts\Authorization;

use App\Http\Contracts\Core\PsInterface;

interface PermissionServiceInterface extends PsInterface
{
    public function checkingForPermissionWithModel($ability, $model, $routeName = null, $msg);

    public function checkingForPermissionWithoutModel($moduleId, $permissionId, $loginUserId, $routeName = null, $msg = null);

    public function checkingForVendorPermission($moduleId, $permissionId, $vendorId, $loginUserId, $routeName = null, $msg = null);

    public function checkingForCreateAbilityWithModel($keyValueArr);

    public function checkingForCreateAbilityWithoutModel($keyValueArr, $loginUserId);

    public function permissionControl($module_id, $permission_id, $loginUserId = null);

    public function authorizationWithoutModel($moduleId, $loginUserId);

    public function vendorAuthorizationWithoutModel($moduleId, $vendorId, $loginUserId = null);

    public function vendorPermissionControl($module_id, $permission_id, $vendorId, $loginUserId = null);
}
