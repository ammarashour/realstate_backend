<?php

namespace Modules\Core\Http\Services\Menu;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Project;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use App\Http\Contracts\Menu\MenuGroupServiceInterface;

class MenuGroupService extends PsService implements MenuGroupServiceInterface
{
    public function save($menuGroupData)
    {

        DB::beginTransaction();

        try {

            $this->saveMenuGroup($menuGroupData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $menuGroupData)
    {
        DB::beginTransaction();

        try {
            $this->updateMenuGroup($id, $menuGroupData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {

            $name = $this->deleteMenuGroup($id);

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
        return CoreMenuGroup::when($id, function ($q, $id) {
            $q->where(CoreMenuGroup::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isHas = null, $ordering = null, $isShowOnMenu = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $project = Project::select("base_project_id")->first();
        $hideVendor =  in_array($project->base_project_id, ps_constant::baseProjectIdsToHideVendor);

        $menu_groups = CoreMenuGroup::when($hideVendor, function ($q) {
                $q->whereNotIn(CoreMenuGroup::id, ps_constant::vendorMenuGroupIdsInAdminPanel);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($isShowOnMenu, function ($q, $isShowOnMenu) {
                $q->where(CoreMenuGroup::isShowOnMenu, $isShowOnMenu);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($isHas, function ($q, $isHas) {
                $q->has($isHas);
            })
            ->when($ordering, function ($q, $ordering) {
                $q->orderBy(CoreMenuGroup::ordering, $ordering);
            })
            ->when(empty($sort) && empty($ordering), function ($query, $conds) {
                $query->orderBy(CoreMenuGroup::isShowOnMenu, 'desc')->orderBy(CoreMenuGroup::groupName, 'asc');
            });
        if ($pagPerPage) {
            $menu_groups = $menu_groups->paginate($pagPerPage)->onEachSide(1)->withQueryString();

        } else {
            $menu_groups = $menu_groups->get();
        }

        return $menu_groups;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateMenuGroup($id, $status);

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

    ///------------------------------------------------------------------
    /// Database
    ///------------------------------------------------------------------
    private function saveMenuGroup($menuGroupData)
    {
        $menuGroup = new CoreMenuGroup();
        $menuGroup->fill($menuGroupData);
        $menuGroup->added_user_id = Auth::user()->id;
        $menuGroup->save();

        return $menuGroup;
    }

    private function updateMenuGroup($id, $menuGroupData)
    {
        $menuGroup = $this->get($id);
        $menuGroup->updated_user_id = Auth::user()->id;
        $menuGroup->update($menuGroupData);

        return $menuGroup;
    }

    private function deleteMenuGroup($id)
    {
        $menuGroup = $this->get($id);
        $name = $menuGroup->group_name;
        $menuGroup->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(CoreMenuGroup::groupName, 'like', '%'.$search.'%');
            });
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(CoreMenuGroup::tableName.'.'.CoreMenuGroup::id, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }

        return $query;
    }
}
