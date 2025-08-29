<?php

namespace Modules\Core\Http\Services\Menu;

use App\Http\Contracts\Configuration\CoreKeyCounterServiceInterface;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorModule;

class VendorModuleService extends PsService implements VendorModuleServiceInterface
{
    public function __construct(protected CoreKeyCounterServiceInterface $coreKeyCounterService)
    {

    }

    /**
     * Manually migrate vendor modules and does not use save function
     */
    public function save($vendorModuleData)
    {
        DB::beginTransaction();

        try {
            $this->saveVendorModule($vendorModuleData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $vendorModuleData)
    {

        DB::beginTransaction();

        try {
            $this->updateVendorModule($id, $vendorModuleData);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteVendorModule($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $subMenuId = null, $isNotUsedModules = null)
    {
        return VendorModule::when($id, function ($q, $id) {
            $q->where(VendorModule::id, $id);
        })
            ->when($subMenuId, function ($q, $subMenuId) {
                $q->where(VendorModule::subMenuId, $subMenuId);
            })
            ->when($isNotUsedModules, function ($q, $isNotUsedModules) {
                $q->where(function ($q) {
                    $q->where(VendorModule::menuId, '=', 0)->orWhere(VendorModule::menuId, '=', null);
                })->where(function ($q) {
                    $q->where(VendorModule::subMenuId, '=', 0)->orWhere(VendorModule::subMenuId, '=', null);
                });
            })
            ->first();
    }

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $status = null, $isNotUsedModules = null, $ids = null, $isNotEmptySubMenuId = null, $isNotEmptyMenuId = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $modules = VendorModule::when($conds, function ($query, $conds) {
            $query = $this->searching($query, $conds);
        })->when($relation, function ($q, $relation) {
            $q->with($relation);
        })->when($status, function ($q, $status) {
            $q->where(VendorModule::status, $status);
        })->when($ids, function ($q, $ids) {
            $q->whereIn(VendorModule::id, $ids);
        })->when($isNotEmptySubMenuId, function ($q, $isNotEmptySubMenuId) {
            $q->where(VendorModule::subMenuId, '!=', 0)
                ->where(VendorModule::subMenuId, '!=', null);
        })->when($isNotEmptyMenuId, function ($q, $isNotEmptyMenuId) {
            $q->where(VendorModule::menuId, '!=', 0)
                ->where(VendorModule::menuId, '!=', null);
        })
        ->when($isNotUsedModules, function ($q, $isNotUsedModules) {
            $q->where(function ($q) {
                $q->where(VendorModule::menuId, '=', 0)->orWhere(VendorModule::menuId, '=', null);
            })->where(function ($q) {
                $q->where(VendorModule::subMenuId, '=', 0)->orWhere(VendorModule::subMenuId, '=', null);
            })->where(function ($q) {
                $q->where(VendorModule::isNotFromSidebar, '=', 0)->orWhere(VendorModule::isNotFromSidebar, '=', null);
            });
        })
        ->when(empty($sort), function ($query, $conds) {
            $query->orderBy(VendorModule::status, 'desc')->orderBy(VendorModule::title, 'asc');
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

            return $this->updateVendorModule($id, $status);
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
    private function saveVendorModule($vendorModuleData)
    {
        $vendorModule = new VendorModule;
        $vendorModule->id = $this->coreKeyCounterService->generate('ps-ven-module');
        $vendorModule->fill($vendorModuleData);
        $vendorModule->added_user_id = Auth::id();
        $vendorModule->save();

        return $vendorModule;
    }

    private function updateVendorModule($id, $vendorModuleData)
    {
        $vendorModule = $this->get($id);
        $vendorModule->updated_user_id = Auth::id();
        $vendorModule->update($vendorModuleData);

        return $vendorModule;
    }

    private function deleteVendorModule($id)
    {
        $vendorModule = $this->get($id);
        $name = $vendorModule->title;
        $vendorModule->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorModule::title, 'like', '%'.$search.'%')
                    ->orWhere(VendorModule::routeName, 'like', '%'.$search.'%');
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
