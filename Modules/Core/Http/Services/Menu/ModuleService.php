<?php

namespace Modules\Core\Http\Services\Menu;

use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Menu\ModuleServiceInterface;

class ModuleService extends PsService implements ModuleServiceInterface
{
    public function __construct(protected MobileSettingServiceInterface $mobileSettingService)
    {

    }

    public function save($moduleData)
    {
        DB::beginTransaction();

        try {

            $this->saveModule($moduleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $moduleData)
    {

        DB::beginTransaction();

        try {
            $this->updateModule($id, $moduleData);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteModule($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $relation = null, $conds = null, $subMenuId = null, $isNotUsedModules = null)
    {
        return Module::when($id, function ($q, $id) {
            $q->where(Module::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($subMenuId, function ($q, $subMenuId) {
                $q->where(Module::subMenuId, $subMenuId);
            })
            ->when($isNotUsedModules, function ($q, $isNotUsedModules) {
                $q->where(function ($q) {
                    $q->where(Module::menuId, '=', 0)->orWhere(Module::menuId, '=', null);
                })->where(function ($q) {
                    $q->where(Module::subMenuId, '=', 0)->orWhere(Module::subMenuId, '=', null);
                });
            })
            ->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $status = null, $isNotUsedModules = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $id = '0';
        $mobileSetting = $this->mobileSettingService->get();
        if ($mobileSetting->is_show_subcategory == '0') {
            $subcategoryModule = Module::where('route_name', 'subcategory.index')->first();
            if ($subcategoryModule) {
                $id = $subcategoryModule->id;
            }
        }

        $modules = Module::wherenot('id', $id)
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->when($status, function ($q, $status) {
                $q->where(Module::status, $status);
            })->when($isNotUsedModules, function ($q, $isNotUsedModules) {
                $q->where(function ($q) {
                    $q->where(Module::menuId, '=', 0)->orWhere(Module::menuId, '=', null);
                })->where(function ($q) {
                    $q->where(Module::subMenuId, '=', 0)->orWhere(Module::subMenuId, '=', null);
                })->where(function ($q) {
                    $q->where(Module::isNotFromSidebar, '=', 0)->orWhere(Module::isNotFromSidebar, '=', null);
                });
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(Module::status, 'desc')->orderBy(Module::title, 'asc');
            });
        if ($pagPerPage) {
            $modules = $modules->paginate($pagPerPage)->onEachSide(1)->withQueryString();

        } else {
            $modules = $modules->get();
        }

        return $modules;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateModule($id, $status);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveModule($moduleData)
    {
        $module = new Module();
        $module->fill($moduleData);
        $module->added_user_id = Auth::user()->id;
        $module->save();

        return $module;
    }

    private function updateModule($id, $moduleData)
    {
        $module = $this->get($id);
        $module->updated_user_id = Auth::user()->id;
        $module->update($moduleData);

        return $module;
    }

    private function deleteModule($id)
    {
        $module = $this->get($id);
        $name = $module->title;
        $module->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Module::title, 'like', '%'.$search.'%')
                    ->orWhere(Module::routeName, 'like', '%'.$search.'%');
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
