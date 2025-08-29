<?php

namespace App\Policies;

use App\Config\ps_constant;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Constants\Constants;

class PsVendorPolicy
{
    use HandlesAuthorization;

    protected $permissionService, $model, $module, $createPermission, $readPermission, $updatePermission, $deletePermission, $loginUserIdPara;
    public function __construct($module, $model=null)
    {
        $this->loginUserIdPara = ps_constant::loginUserIdParaFromApi;
        $this->createPermission = ps_constant::createPermission;
        $this->readPermission = ps_constant::readPermission;
        $this->updatePermission = ps_constant::updatePermission;
        $this->deletePermission = ps_constant::deletePermission;
        $this->module = $module;
        $this->model = $model;
        $this->permissionService = app(PermissionServiceInterface::class);
    }

    public function viewAny(User $user)
    {
        $vendor_id = getVendorIdFromSession();

        $canCreate = $this->permissionService->vendorPermissionControl($this->module, $this->createPermission, $vendor_id);
        $canUpdate = $this->permissionService->vendorPermissionControl($this->module, $this->updatePermission, $vendor_id);
        $canDelete = $this->permissionService->vendorPermissionControl($this->module, $this->deletePermission, $vendor_id);
        $Can = $this->permissionService->vendorPermissionControl($this->module, $this->readPermission, $vendor_id);

        if($canCreate || $canUpdate || $canDelete || $Can) {
            return true;
        }
    }

    public function create(User $user)
    {
        $vendor_id = getVendorIdFromSession();

        $Can = $this->permissionService->vendorPermissionControl($this->module, $this->createPermission, $vendor_id);
        if ($Can) {
            return true;
        }
    }

    public function update(User $user, $model=null)
    {
        $vendor_id = getVendorIdFromSession();

        $Can = $this->permissionService->vendorPermissionControl($this->module, $this->updatePermission, $vendor_id);
        if ($Can) {
            return true;
        }
        if (!empty($this->model)){
            $userId = getLoginUserId($this->loginUserIdPara, $user->id);
            return $userId == $model->added_user_id;
        } else {
            return false;
        }
    }

    public function delete(User $user, $model=null)
    {
        $vendor_id = getVendorIdFromSession();

        $Can = $this->permissionService->vendorPermissionControl($this->module, $this->deletePermission, $vendor_id);
        if ($Can) {
            return true;
        }
        if (!empty($this->model)){
            $userId = getLoginUserId($this->loginUserIdPara, $user->id);
            return $userId == $model->added_user_id;
        } else {
            return false;
        }
    }
}
