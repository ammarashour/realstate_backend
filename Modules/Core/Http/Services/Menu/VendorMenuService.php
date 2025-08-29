<?php

namespace Modules\Core\Http\Services\Menu;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorMenu;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use App\Http\Contracts\Menu\VendorMenuServiceInterface;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;

class VendorMenuService extends PsService implements VendorMenuServiceInterface
{
    public function __construct(protected VendorModuleServiceInterface $vendorModuleService)
    {}

    public function save($vendorMenuData)
    {

        DB::beginTransaction();

        try {

            $coreMenu = $this->saveVendorMenu($vendorMenuData);

            // update menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData($coreMenu->id);
            $this->vendorModuleService->update($vendorMenuData['module_id'], $moduleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $vendorMenuData)
    {
        DB::beginTransaction();

        try {
            $coreMenu = $this->updateVendorMenu($id, $vendorMenuData);

            // update old menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData(0);
            $this->vendorModuleService->update($vendorMenuData['old_module_id'], $moduleData);

            // update menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData($coreMenu->id);
            $this->vendorModuleService->update($vendorMenuData['module_id'], $moduleData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $vendorMenu = $this->get($id);

            $name = $this->deleteVendorMenu($id);

            // update old menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData(0);
            $this->vendorModuleService->update($vendorMenu->module_id, $moduleData);

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
        return VendorMenu::when($id, function($query, $id){
            $query->where(VendorMenu::id, $id);
        })
        ->when($relation, function($query, $relation){
            $query->with($relation);
        })
        ->when($conds, function($query, $conds){
            $query->where($conds);
        })
        ->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isShowOnMenu = null, $ids = null, $ordering = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $vendorMenus = VendorMenu::select(VendorMenu::tableName.'.*')
                    ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                        if ($sort == 'core_sub_menu_group_id@@sub_menu_desc') {
                            $q->join(VendorSubMenuGroup::tableName, VendorSubMenuGroup::tableName.'.'.VendorSubMenuGroup::id, '=', VendorMenu::tableName.'.'.VendorMenu::coreSubMenuGroupId);
                            $q->select(VendorSubMenuGroup::tableName.'.'.VendorSubMenuGroup::subMenuDesc .' as sub_menu_desc', VendorMenu::tableName.'.*');
                        }
                    })
                    ->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })
                    ->when($relation, function ($q, $relation){
                        $q->with($relation);
                    })
                    ->when($ids, function ($q, $ids){
                        $q->whereIn(VendorMenu::id, $ids);
                    })
                    ->when($isShowOnMenu !== null, function ($q) use ($isShowOnMenu) {
                        if ($isShowOnMenu !== null) {
                            $q->where(VendorMenu::isShowOnMenu, $isShowOnMenu);
                        }
                    })
                    ->when($ordering, function ($query, $ordering){
                        $query->orderBy(VendorMenu::ordering, $ordering);
                    })
                    ->when(empty($sort) && empty($ordering), function ($query, $conds){
                        $query->orderBy(VendorMenu::t(VendorMenu::isShowOnMenu), 'desc')->orderBy(VendorMenu::t(VendorMenu::moduleName), 'asc');
                    });
        if ($pagPerPage){
            $vendorMenus = $vendorMenus->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else{
            $vendorMenus = $vendorMenus->get();
        }
        return $vendorMenus;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateVendorMenu($id, $status);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ///////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data prepare
    //-------------------------------------------------------------------
    private function prepareUpdateStausData($status)
    {
        return [
            'is_show_on_menu' => $status
        ];
    }

    private function prepareUpdateModuleData($menuId)
    {
        return ['menu_id' => $menuId];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveVendorMenu($vendorMenuData)
    {
        $vendorMenu = new VendorMenu();
        $vendorMenu->fill($vendorMenuData);
        $vendorMenu->added_user_id = Auth::id();
        $vendorMenu->save();

        return $vendorMenu;
    }

    private function updateVendorMenu($id, $vendorMenuData)
    {
        $vendorMenu = $this->get($id);
        $vendorMenu->updated_user_id = Auth::id();
        $vendorMenu->update($vendorMenuData);

        return $vendorMenu;
    }

    private function deleteVendorMenu($id)
    {
        $vendorMenu = $this->get($id);
        $name = $vendorMenu->module_desc;
        $vendorMenu->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorMenu::moduleName,'like','%'.$search.'%')
                ->orwhere(VendorMenu::moduleDesc,'like','%'.$search.'%');
            });
        }
        if(isset($conds['sub_menu_id']) && $conds['sub_menu_id'] != 'all') {
           $query->where(VendorMenu::coreSubMenuGroupId, $conds['sub_menu_id']);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id'){
                $query->orderBy('owner', $conds['order_type']);
            } else if ($conds['order_by'] == 'core_sub_menu_group_id@@sub_menu_desc') {
                $query->orderBy('sub_menu_desc', $conds['order_type']);
            } else{
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }
        return $query;
    }
}
