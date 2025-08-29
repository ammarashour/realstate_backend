<?php

namespace Modules\Core\Http\Services\Menu;

use App\Http\Contracts\Menu\VendorMenuGroupServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorMenuGroup;

class VendorMenuGroupService extends PsService implements VendorMenuGroupServiceInterface
{
    public function save($vendorMenuGroupData)
    {
        DB::beginTransaction();

        try {

            $this->saveVendorMenuGroup($vendorMenuGroupData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $vendorMenuGroupData)
    {
        DB::beginTransaction();

        try {

            $this->updateVendorMenuGroup($id, $vendorMenuGroupData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteVendorMenuGroup($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $relation = null, $conds = null)
    {
        return VendorMenuGroup::when($id, function ($q, $id) {
            $q->where(VendorMenuGroup::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->with($conds);
            })->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isShowOnMenu = null, $ids = null, $ordering = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $vendorMenuGroups = VendorMenuGroup::when($conds, function ($query, $conds) {
            $query = $this->searching($query, $conds);
        })->when($relation, function ($q, $relation) {
            $q->with($relation);
        })
        ->when($ids, function ($q, $ids){
            $q->whereIn(VendorMenuGroup::id, $ids);
        })
        ->when($isShowOnMenu !== null, function ($q) use ($isShowOnMenu) {
            if ($isShowOnMenu !== null) {
                $q->where(VendorMenuGroup::isShowOnMenu, $isShowOnMenu);
            }
        })
        ->when($ordering, function ($query, $ordering){
            $query->orderBy(VendorMenuGroup::ordering, $ordering);
        })
        ->when(empty($sort) && empty($ordering), function ($query, $conds) {
            $query->orderBy(VendorMenuGroup::isShowOnMenu, 'desc')->orderBy(VendorMenuGroup::groupName, 'asc');
        });
        if ($pagPerPage) {
            $vendorMenuGroups = $vendorMenuGroups->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $vendorMenuGroups = $vendorMenuGroups->get();
        }

        return $vendorMenuGroups;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateVendorMenuGroup($id, $status);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ///////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareUpdateStausData($status)
    {
        return ['is_show_on_menu' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveVendorMenuGroup($vendorMenuGroupData)
    {
        $coreMenu = new VendorMenuGroup;
        $coreMenu->fill($vendorMenuGroupData);
        $coreMenu->added_user_id = Auth::user()->id;
        $coreMenu->save();

        return $coreMenu;
    }

    private function updateVendorMenuGroup($id, $vendorMenuGroupData)
    {
        $vendorMenuGroup = $this->get($id);
        $vendorMenuGroup->updated_user_id = Auth::user()->id;
        $vendorMenuGroup->update($vendorMenuGroupData);

        return $vendorMenuGroup;
    }

    private function deleteVendorMenuGroup($id)
    {
        $vendorMenuGroup = $this->get($id);
        $name = $vendorMenuGroup->group_name;
        $vendorMenuGroup->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorMenuGroup::groupName, 'like', '%'.$search.'%');
            });
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }

        return $query;
    }
}
