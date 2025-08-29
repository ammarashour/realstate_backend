<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Http\Contracts\Vendor\VendorRoleServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorRole;
use Modules\Core\Entities\Vendor\VendorRolePermission;
use App\Http\Contracts\Vendor\VendorRolePermissionServiceInterface;


class VendorRoleService extends PsService implements VendorRoleServiceInterface
{
    public function __construct(protected VendorRolePermissionServiceInterface $vendorRolePermissionService) {}

    public function save($roleData)
    {
        DB::beginTransaction();
        try {

            $role = $this->saveVendorRole($roleData);

           $vendorRolePermissionData = $this->prepareVendorRolePermissionData($roleData, $role);

           $this->vendorRolePermissionService->save($vendorRolePermissionData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $roleData)
    {
        try {
            //update vendor role
            $role = $this->updateVendorRole($id, $roleData);

            $vendorRolePermissionData = $this->prepareVendorRolePermissionData($roleData, $role);

           $this->vendorRolePermissionService->update($role->id, $vendorRolePermissionData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id)
    {
        return VendorRole::find($id);
    }

    public function getAll($relations = null, $conds = null, $noPagination = null, $pagPerPage = null, $sort = null, $roleIds = null, $status = null)
    {
        $roles = VendorRole::when($relations, function ($q, $relations) {
            $q->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })

            ->when($roleIds, function ($q, $roleIds) {
                $q->whereIn(VendorRole::id, $roleIds);
            })
            ->when($status !== null, function ($q, $status) {
                $q->where(VendorRole::status, $status);
            })
            ->latest();
        if ($pagPerPage) {
            $roles = $roles->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $roles = $roles->get();
        } else {
            $roles = $roles->get();
        }

        return $roles;
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteVendorRoleAndPermission($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $vendorStatus = $this->prepareUpdateStausData($status);

            $vendorRole = $this->get($id);
            $vendorRole->status = $vendorStatus['status'];
            $vendorRole->update();

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    private function prepareVendorRolePermissionData($roleData, $role)
    {
        $module_and_permission = new \stdClass();

        foreach ($roleData['permissionObj'] as $permission) {
            if ($permission['value'] != null || $permission['value'] != '') {
                $module_and_permission->{$permission['key']} = explode(',', $permission['value']);
            }
        }
       return [
        'vendor_role_id' => $role->id,
        'module_and_permission' => json_encode($module_and_permission)
       ];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveVendorRole($roleData)
    {
        $role = new VendorRole();
        $role->name = $roleData['name'];
        $role->description = $roleData['description'];
        $role->status = 1;
        $role->added_user_id = Auth::user()->id;
        $role->save();

        return $role;
    }


    private function updateVendorRole($id, $roleData)
    {
        //update role name
        $role = VendorRole::find($id);
        $role->name = $roleData['name'];
        $role->description = $roleData['description'];
        $role->updated_user_id = Auth::user()->id;
        $role->update();

        return $role;
    }



    private function deleteVendorRoleAndPermission($id)
    {
        $role = $this->get($id);
        $name = $role->name;
        $role->delete();

        //delete all old permission
        $roleOldPermissionIds = VendorRolePermission::where('vendor_role_id', $role->id)->get()->pluck('id');
        VendorRolePermission::destroy($roleOldPermissionIds);

        return $name;
    }

    private function searching($query, $conds)
    {
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        // searchterm
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorRole::name, 'like', '%'.$search.'%');
                $query->orWhere(VendorRole::description, 'like', '%'.$search.'%');
            });
        }

        if (isset($conds['status'])) {
            $query->where('status', $conds['status']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy(VendorRole::name.'.'.VendorRole::id, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(VendorRole::tableName.'.'.VendorRole::name, 'asc')->orderBy(VendorRole::tableName.'.'.VendorRole::status, 'asc');
        }

        return $query;
    }
}
