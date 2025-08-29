<?php

namespace Modules\Core\Http\Services\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Menu\CoreMenu;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Entities\Utilities\CoreField;
use App\Http\Contracts\Menu\ModuleServiceInterface;
use App\Http\Contracts\Menu\CoreMenuServiceInterface;

class CoreMenuService extends PsService implements CoreMenuServiceInterface
{
    public function __construct(protected ModuleServiceInterface $moduleService,
        protected MobileSettingServiceInterface $mobileSettingService) {}

    public function save($coreMenuData)
    {

        DB::beginTransaction();

        try {

            $coreMenu = $this->saveCoreMenu($coreMenuData);

            // update menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData($coreMenu->id);
            $this->moduleService->update($coreMenuData['module_id'], $moduleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $coreMenuData)
    {
        DB::beginTransaction();

        try {
            $coreMenu = $this->updateCoreMenu($id, $coreMenuData);

            // update old menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData(0);
            $this->moduleService->update($coreMenuData['old_module_id'], $moduleData);

            // update menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData($coreMenu->id);
            $this->moduleService->update($coreMenuData['module_id'], $moduleData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $coreMenu = $this->get($id);

            $name = $this->deleteCoreMenu($id);

            // update old menu_id at psx_modules table
            $moduleData = $this->prepareUpdateModuleData(0);
            $this->moduleService->update($coreMenu->module_id, $moduleData);

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
        return CoreMenu::when($id, function ($q, $id) {
            $q->where(CoreMenu::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $ids = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $id = '0';
        $project = Project::select('base_project_id')->first();
        $baseProjectIdsToHideVendor = ps_constant::baseProjectIdsToHideVendor;

        $mobileSetting = $this->mobileSettingService->get();
        if ($mobileSetting->is_show_subcategory == '0') {
            $subcategoryModule = Module::where('route_name', 'subcategory.index')->first();
            if ($subcategoryModule) {
                $subcategoryMenu = CoreMenu::where('module_id', $subcategoryModule->id)->first();
                if ($subcategoryMenu) {
                    $id = $subcategoryMenu->id;
                }
            }
        }

        $coreMenus = CoreMenu::select(CoreMenu::tableName.'.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'core_sub_menu_group_id@@sub_menu_desc') {
                    $q->join(CoreSubMenuGroup::tableName, CoreSubMenuGroup::tableName.'.'.CoreSubMenuGroup::id, '=', CoreMenu::tableName.'.'.CoreMenu::coreSubMenuGroupId);
                    $q->select(CoreSubMenuGroup::tableName.'.'.CoreSubMenuGroup::subMenuDesc .' as sub_menu_desc', CoreMenu::tableName.'.*');
                }
            })
            ->when($ids, function ($query, $ids) {
                $query->whereIn(CoreMenu::id, $ids);
            })
            ->where(function ($q) use ($project, $baseProjectIdsToHideVendor) {
                if (in_array($project->base_project_id, $baseProjectIdsToHideVendor)) {
                    $q->whereNotIn(CoreMenu::id, ps_constant::vendorMenuIdsInAdminPanel);
                }
            })
            ->wherenot(CoreMenu::tableName.'.'.CoreMenu::id, $id)
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(CoreMenu::isShowOnMenu, 'desc')->orderBy(CoreMenu::moduleName, 'asc');
            });
        if ($pagPerPage) {
            $coreMenus = $coreMenus->paginate($pagPerPage)->onEachSide(1)->withQueryString();

        } else {
            $coreMenus = $coreMenus->get();
        }

        return $coreMenus;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateCoreMenu($id, $status);

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

    private function prepareUpdateModuleData($menuId)
    {
        return ['menu_id' => $menuId];
    }

    ///------------------------------------------------------------------
    /// Database
    ///------------------------------------------------------------------
    private function saveCoreMenu($coreMenuData)
    {
        $coreMenu = new CoreMenu();
        $coreMenu->fill($coreMenuData);
        $coreMenu->added_user_id = Auth::user()->id;
        $coreMenu->save();

        return $coreMenu;
    }

    private function updateCoreMenu($id, $coreMenuData)
    {
        $coreMenu = $this->get($id);
        $coreMenu->updated_user_id = Auth::user()->id;
        $coreMenu->update($coreMenuData);

        return $coreMenu;
    }

    private function deleteCoreMenu($id)
    {
        $coreMenu = $this->get($id);
        $name = $coreMenu->module_desc;
        $coreMenu->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(CoreField::moduleName, 'like', '%'.$search.'%')
                    ->orwhere('module_desc', 'like', '%'.$search.'%');
            });
        }
        if (isset($conds['sub_menu_id']) && $conds['sub_menu_id'] != 'all') {
            $query->where('core_sub_menu_group_id', $conds['sub_menu_id']);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else if($conds['order_by'] == 'core_sub_menu_group_id@@sub_menu_desc') {
                $query->orderBy('sub_menu_desc', $conds['order_type']);
            }else{
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        } else {
            $query->orderBy('is_show_on_menu', 'desc')->orderBy(CoreField::moduleName, 'asc');
        }

        return $query;
    }
}
