<?php

namespace Modules\Core\Http\Services\Vendor;

use Modules\Core\Entities\Vendor\VendorRolePermission;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Contracts\Vendor\VendorRolePermissionServiceInterface;

class VendorRolePermissionService extends PsService implements VendorRolePermissionServiceInterface
{
    public function __construct() {}

    public function save($VendorRolePermissionData)
    {
        DB::beginTransaction();
        try{

            $this->saveVendorRolePermission($VendorRolePermissionData);

            DB::commit();

        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function update($roleId, $VendorRolePermissionData)
    {
        DB::beginTransaction();
        try{

            $this->deleteAll($roleId);

            $this->saveVendorRolePermission($VendorRolePermissionData);

            DB::commit();

        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }


    public function getAll($id)
    {
        return VendorRolePermission::where('vendor_role_id', $id)->get()->pluck('id');
    }


    public function deleteAll($id)
    {
        $roleOldPermissionIds = $this->getAll($id);
        VendorRolePermission::destroy($roleOldPermissionIds);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveVendorRolePermission($VendorRolePermissionData)
    {
        $rolePermissions = new VendorRolePermission();
        $rolePermissions->vendor_role_id = $VendorRolePermissionData['vendor_role_id'];
        $rolePermissions->module_and_permission = $VendorRolePermissionData['module_and_permission'];
        $rolePermissions->added_user_id = Auth::user()->id;
        $rolePermissions->save();
    }

}
