<?php

namespace Modules\Core\Http\services\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\ModuleServiceInterface;
use App\Http\Contracts\Menu\SubMenuGroupServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Entities\Project;

class SubMenuGroupService extends PsService implements SubMenuGroupServiceInterface
{
    public function __construct(protected ModuleServiceInterface $moduleService) {}

    public function save($subMenuGroupData)
    {

        DB::beginTransaction();

        try {

            $subMenuGroup = $this->saveSubMenuGroup($subMenuGroupData);

            $moduleData = $this->prepareModuleData($subMenuGroup->id);
            $this->moduleService->update($subMenuGroupData['module_id'], $moduleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $subMenuGroupData)
    {
        DB::beginTransaction();

        try {
            $subMenuGroup = $this->get($id);
            $moduleData = $this->prepareModuleData(0);
            $this->moduleService->update($subMenuGroup->module_id, $moduleData);

            $moduleData = $this->prepareModuleData($id);
            $this->moduleService->update($subMenuGroupData['module_id'], $moduleData);

            $this->updateSubMenuGroup($id, $subMenuGroupData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $subMenuGroup = $this->get($id);

            $name = $this->deleteSubMenuGroup($id);

            // update old sub_menu_id at psx_modules table
            $moduleData = $this->prepareModuleData(0);
            $this->moduleService->update($subMenuGroup->module_id, $moduleData);

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
        return CoreSubMenuGroup::when($id, function ($query, $id) {
            $query->where(CoreSubMenuGroup::id, $id);
        })
            ->when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $whereNullData = null, $ordering = null)
    {
        $sort = '';
        $project = Project::select('base_project_id')->first();
        $baseProjectIdsToHideVendor = ps_constant::baseProjectIdsToHideVendor;
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $subMenuGroups = CoreSubMenuGroup::select('psx_core_sub_menu_groups.*')
            ->where(function ($q) use ($project, $baseProjectIdsToHideVendor) {
                if (in_array($project->base_project_id, $baseProjectIdsToHideVendor)) {
                    $q->whereNotIn(CoreSubMenuGroup::id, ps_constant::vendorSubMenuIdsInAdminPanel);
                }
            })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                // if($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id')
                // {
                //     $q->leftJoin($this->userTableName, $this->userTableName.'.'.$this->userIdCol, '=', $this->langTableName.'.'.$conds['order_by']);
                //     $q->select($this->userTableName.'.'.$this->userNameCol.' as owner', $this->langTableName.'.*');

                // }
                if ($conds['order_by'] == 'core_menu_group_id@@group_name') {
                    $q->join(CoreMenuGroup::tableName, CoreMenuGroup::tableName.'.'.CoreMenuGroup::id, '=', CoreSubMenuGroup::tableName.'.'.CoreSubMenuGroup::coreMenuGroupId);
                    $q->select(CoreMenuGroup::tableName.'.'.CoreMenuGroup::groupName.' as menu_group_name', CoreSubMenuGroup::tableName.'.*');
                }
            })->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($whereNullData, function ($q, $whereNullData) {
                $q->whereNull($whereNullData);
            })
            ->when($ordering, function ($q, $ordering) {
                $q->orderBy(CoreSubMenuGroup::ordering, $ordering);
            })
            ->when(empty($sort) && empty($ordering), function ($query, $conds) {
                $query->orderBy(CoreSubMenuGroup::isShowOnMenu, 'desc')->orderBy(CoreSubMenuGroup::subMenuName, 'asc');
            });
        if ($pagPerPage) {
            $subMenuGroups = $subMenuGroups->paginate($pagPerPage)->onEachSide(1)->withQueryString();

        } else {
            $subMenuGroups = $subMenuGroups->get();
        }

        return $subMenuGroups;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateSubMenuGroup($id, $status);

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

    private function prepareModuleData($subMenuId)
    {
        return ['sub_menu_id' => $subMenuId];
    }

    ///------------------------------------------------------------------
    /// Database
    ///------------------------------------------------------------------
    private function saveSubMenuGroup($subMenuGroupData)
    {
        $subMenuGroup = new CoreSubMenuGroup();
        $subMenuGroup->fill($subMenuGroupData);
        $subMenuGroup->added_user_id = Auth::user()->id;
        $subMenuGroup->save();

        return $subMenuGroup;
    }

    private function updateSubMenuGroup($id, $subMenuGroupData)
    {
        $subMenuGroup = $this->get($id);
        $subMenuGroup->updated_user_id = Auth::user()->id;
        $subMenuGroup->update($subMenuGroupData);

        return $subMenuGroup;
    }

    private function deleteSubMenuGroup($id)
    {
        $subMenuGroup = $this->get($id);
        $name = $subMenuGroup->sub_menu_desc;
        $subMenuGroup->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where('sub_menu_name', 'like', '%'.$search.'%');
            });
        }
        if (isset($conds['menu_id']) && $conds['menu_id'] != 'all') {
            $query->where(CoreSubMenuGroup::coreMenuGroupId, $conds['menu_id']);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } elseif ($conds['order_by'] == 'core_menu_group_id@@group_name') {
                $query->orderBy('menu_group_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }

        return $query;
    }
}
