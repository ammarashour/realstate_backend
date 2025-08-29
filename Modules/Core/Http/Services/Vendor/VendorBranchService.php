<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Http\Contracts\Vendor\VendorBranchServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Entities\Vendor\VendorBranch;


class VendorBranchService extends PsService implements VendorBranchServiceInterface
{
    public function __construct() {}

    public function save($vendorBranchData)
    {

        DB::beginTransaction();

        try {
            // save in location city table
            $this->saveVendorBranch($vendorBranchData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $vendorBranchData)
    {

        DB::beginTransaction();

        try {
            // save in location city table
            $vendorBranch = $this->updateVendorBranch($id, $vendorBranchData);

            DB::commit();

            return $vendorBranch;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function get($id, $relation = null)
    {
        $vendorBranch = VendorBranch::when($relation != null, function ($query) use ($relation) {
            $query->with($relation);
        })->where(Vendor::id, $id)->first();

        return $vendorBranch;
    }

    public function getAll($limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $vendorBranches = VendorBranch::select(VendorBranch::tableName . '.*')
            ->when(isset($conds['vendor_id']) && $conds['vendor_id'], function ($query) use ($conds) {
                $query->where(VendorBranch::vendorId, $conds['vendor_id']);
            })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == VendorBranch::vendorId . '@@name') {
                    $q->join(Vendor::tableName, Vendor::tableName . '.' . Vendor::id, '=', VendorBranch::vendorId);
                    $q->select(Vendor::tableName . '.' . Vendor::name . ' as vendor_name', Vendor::tableName . '.*');
                }
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(VendorBranch::tableName . '.added_date', 'desc')->orderBy(VendorBranch::tableName . '.' . VendorBranch::name, 'asc');
            });
        if ($pagPerPage) {
            $vendorBranches = $vendorBranches->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $vendorBranches = $vendorBranches->get();
        }

        return $vendorBranches;
    }

    public function delete($id)
    {
        try {
            //delete in location_cities table
            $vendorBranch = $this->deleteVendorBranch($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $vendorBranch]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteAll($vendorId, $excludedBranchIds)
    {
        $conds = [
            'vendor_id' => $vendorId
        ];
        $branches = $this->getAll(conds: $conds);
        foreach($branches as $branch)
        {
            if (!in_array($branch->id, $excludedBranchIds)) {
                $branch->delete();
            }
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorBranch::tableName . '.' . VendorBranch::name, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds[VendorBranch::vendorId]) && $conds[VendorBranch::vendorId]) {
            $query->where(VendorBranch::tableName . '.' . VendorBranch::vendorId, $conds[VendorBranch::vendorId]);
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(VendorBranch::tableName . '.' . VendorBranch::addedUserId, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(VendorBranch::tableName . '.' . VendorBranch::id, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    private function saveVendorBranch($vendorBranchData)
    {
        $newBranch = new VendorBranch();
        $newBranch->fill($vendorBranchData);
        $newBranch->added_user_id = Auth::user()->id;
        $newBranch->save();
    }

    private function updateVendorBranch($id, $vendorBranchData)
    {
        $vendorBranch = $this->get($id);
        $vendorBranch->updated_user_id = Auth::user()->id;
        $vendorBranch->update($vendorBranchData);
        return $vendorBranch;
    }

    private function deleteVendorBranch($id)
    {
        $vendorBranch = $this->get($id);
        $name = $vendorBranch->name;
        $vendorBranch->delete();

        return $name;
    }
}
