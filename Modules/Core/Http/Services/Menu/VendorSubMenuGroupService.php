<?php

namespace Modules\Core\Http\Services\Menu;

use App\Http\Contracts\Menu\VendorModuleServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use App\Http\Contracts\Menu\VendorSubMenuGroupServiceInterface;

class VendorSubMenuGroupService extends PsService implements VendorSubMenuGroupServiceInterface
{
    public function __construct(protected VendorModuleServiceInterface $vendorModuleService)
    {

    }

    public function save($vendorSubMenuGroupData)
    {
        DB::beginTransaction();

        try {

            $vendorSubMenuGroup = $this->saveVendorSubMenuGroup($vendorSubMenuGroupData);

            $moduleData = $this->prepareModuleData($vendorSubMenuGroup->id);
            $this->vendorModuleService->update($vendorSubMenuGroupData['module_id'], $moduleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $vendorSubMenuGroupData)
    {
        DB::beginTransaction();

        try {
            $subMenuGroup = $this->get($id);
            $moduleData = $this->prepareModuleData(0);
            $this->vendorModuleService->update($subMenuGroup->module_id, $moduleData);

            $moduleData = $this->prepareModuleData($id);
            $this->vendorModuleService->update($vendorSubMenuGroupData['module_id'], $moduleData);

            $this->updateVendorSubMenuGroup($id, $vendorSubMenuGroupData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $subMenuGroup = $this->get($id);

            $name = $this->deleteVendorSubMenuGroup($id);

            // update old sub_menu_id at psx_modules table
            $moduleData = $this->prepareModuleData(0);
            $this->vendorModuleService->update($subMenuGroup->module_id, $moduleData);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $relation = null)
    {
        return VendorSubMenuGroup::when($id, function ($q, $id) {
                $q->where(VendorSubMenuGroup::id, $id);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isDropdown = null, $ids = null, $isShowOnMenu = null, $ordering = null)
    {
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }

        $vendorSubMenuGroups = VendorSubMenuGroup::select(VendorSubMenuGroup::tableName.'.*')
                        ->when(isset($conds['order_by']) && $conds['order_by'] , function ($q) use($conds){
                            if($conds['order_by'] == 'core_menu_group_id@@group_name')
                            {
                                $q->join(VendorMenuGroup::tableName, VendorMenuGroup::t(VendorMenuGroup::id), '=', VendorSubMenuGroup::t(VendorSubMenuGroup::coreMenuGroupId));
                                $q->select(VendorMenuGroup::t(VendorMenuGroup::groupName).' as menu_group_name', VendorSubMenuGroup::tableName.'.*');
                            }
                        })->when($conds, function ($query, $conds) {
                            $query = $this->searching($query, $conds);
                        })->when($relation, function ($q, $relation){
                            $q->with($relation);
                        })->when($isDropdown !== null, function ($q) use ($isDropdown) {
                            if ($isDropdown !== null) {
                                $q->where(VendorSubMenuGroup::isDropdown, $isDropdown);
                            }
                        })->when($ids, function ($q, $ids){
                            $q->whereIn(VendorSubMenuGroup::id, $ids);
                        })->when($isShowOnMenu !== null, function ($q) use ($isShowOnMenu) {
                            if ($isShowOnMenu !== null) {
                                $q->where(VendorSubMenuGroup::isShowOnMenu, $isShowOnMenu);
                            }
                        })
                        ->when($ordering, function ($query, $ordering){
                            $query->orderBy(VendorSubMenuGroup::ordering, $ordering);
                        })
                        ->when(empty($sort) && empty($ordering), function ($query, $conds){
                            $query->orderBy(VendorSubMenuGroup::t(VendorSubMenuGroup::isShowOnMenu), 'desc')->orderBy(VendorSubMenuGroup::t(VendorSubMenuGroup::subMenuName), 'asc');
                        });
            if ($pagPerPage){
                $vendorSubMenuGroups = $vendorSubMenuGroups->paginate($pagPerPage)->onEachSide(1)->withQueryString();

            } else{
                $vendorSubMenuGroups = $vendorSubMenuGroups->get();
            }
        return $vendorSubMenuGroups;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateVendorSubMenuGroup($id, $status);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ///////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------
    private function prepareUpdateStausData($status)
    {
        return ['is_show_on_menu' => $status];
    }

    private function prepareModuleData($subMenuId)
    {
        return ['sub_menu_id' => $subMenuId];
    }

    ///-----------------------------------------------------------
    /// Database
    ///-----------------------------------------------------------
    private function saveVendorSubMenuGroup($vendorSubMenuGroupData)
    {
        $vendorSubMenuGroup = new VendorSubMenuGroup();
        $vendorSubMenuGroup->fill($vendorSubMenuGroupData);
        $vendorSubMenuGroup->added_user_id = Auth::id();
        $vendorSubMenuGroup->save();

        return $vendorSubMenuGroup;
    }

    private function updateVendorSubMenuGroup($id, $vendorSubMenuGroupData)
    {
        $vendorSubMenuGroup = $this->get($id);
        $vendorSubMenuGroup->updated_user_id = Auth::id();
        $vendorSubMenuGroup->update($vendorSubMenuGroupData);

        return $vendorSubMenuGroup;
    }

    private function deleteVendorSubMenuGroup($id)
    {
        $vendorSubMenuGroup = $this->get($id);
        $name = $vendorSubMenuGroup->sub_menu_name;
        $vendorSubMenuGroup->delete();

        return $name;
    }

    private function searching($query, $conds){
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where('sub_menu_name','like','%'.$search.'%');
            });
        }
        if(isset($conds['menu_id'])  && $conds['menu_id'] != "all")
        {
           $query->where(VendorSubMenuGroup::coreMenuGroupId, $conds['menu_id']);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']){

            if($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id'){
                $query->orderBy('owner', $conds['order_type']);
            } elseif($conds['order_by'] == 'core_menu_group_id@@group_name'){
                $query->orderBy('menu_group_name', $conds['order_type']);
            } elseif($conds['order_by'] == 'sub_menu_icon'){
                $query->orderBy('icon_id', $conds['order_type']);
            } else{
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }
        return $query;
    }
}
