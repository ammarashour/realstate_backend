<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Http\Services\PsService;
use App\Http\Contracts\Vendor\VendorUserPermissionServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Vendor\VendorUserPermission;

class VendorUserPermissionService extends PsService implements VendorUserPermissionServiceInterface
{

    public function save($vendorUserPermissionData)
    {
        DB::beginTransaction();
        try {

            // save category
            $category = $this->saveVendorUserPermission($vendorUserPermissionData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function get($userId, $relation = null)
    {
        return VendorUserPermission::when($userId, function ($q, $userId) {
                $q->where(VendorUserPermission::userId, $userId);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveVendorUserPermission($vendorUserPermissionData)
    {
        $vendorUserPermission = new VendorUserPermission();
        $vendorUserPermission->fill($vendorUserPermissionData);
        $vendorUserPermission->added_user_id = Auth::user()->id;
        $vendorUserPermission->save();

        return $vendorUserPermission;
    }
}
