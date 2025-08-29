<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Http\Contracts\Utilities\DynamicColumnVisibilityServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;

class DynamicColumnVisibilityService extends PsService implements DynamicColumnVisibilityServiceInterface
{
    public function __construct() {}

    public function save($dynamicColumnVisibilityData)
    {
        $dynamicColumnVisibility = new DynamicColumnVisibility();
        $dynamicColumnVisibility->module_name = $dynamicColumnVisibilityData->module_name;
        $dynamicColumnVisibility->key = $dynamicColumnVisibilityData->key;
        $dynamicColumnVisibility->is_show = $dynamicColumnVisibilityData->is_show;
        $dynamicColumnVisibility->added_user_id = Auth::id();
        $dynamicColumnVisibility->save();

        return $dynamicColumnVisibility;
    }

    public function update($id, $dynamicColumnVisibilityData)
    {
        $dynamicColumnVisibility = $this->get($id);
        $dynamicColumnVisibility->updated_user_id = Auth::id();
        $dynamicColumnVisibility->update($dynamicColumnVisibilityData);

        return $dynamicColumnVisibility;
    }

    public function delete($id)
    {
        $dynamicColumnVisibility = $this->get($id);
        $moduleName = $dynamicColumnVisibility->module_name;
        $dynamicColumnVisibility->delete();

        return $moduleName;
    }

    public function get($id = null, $key = null, $moduleName = null)
    {
        $dynamicColumnVisibility = DynamicColumnVisibility::when($id, function ($q, $id) {
            $q->where(DynamicColumnVisibility::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(DynamicColumnVisibility::key, $key);
            })
            ->when($moduleName, function ($q, $moduleName) {
                $q->where(DynamicColumnVisibility::moduleName, $moduleName);
            })
            ->first();

        return $dynamicColumnVisibility;
    }

    public function getAll($relation = null, $moduleName = null, $key = null, $isShow = null, $noPagination = null, $pagPerPage = null)
    {
        $dynamicColumnVisibilities = DynamicColumnVisibility::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($moduleName, function ($q, $moduleName) {
                $q->where(DynamicColumnVisibility::moduleName, $moduleName);
            })
            ->when($key, function ($q, $key) {
                $q->where(DynamicColumnVisibility::key, $key);
            })
            ->when($isShow !== null, function ($query) use ($isShow) {
                $query->where(DynamicColumnVisibility::isShow, $isShow);
            });
        if ($pagPerPage) {
            return $dynamicColumnVisibilities->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $dynamicColumnVisibilities->get();
        }
    }

    public function updateOrCreate($dataArrWhere, $dynamicColumnVisibilityData)
    {
        DynamicColumnVisibility::unguard();
        $dynamicColumnVisibility = DynamicColumnVisibility::updateOrCreate(
            $dataArrWhere,
            [
                DynamicColumnVisibility::moduleName => $dynamicColumnVisibilityData->module_name,
                DynamicColumnVisibility::key => $dynamicColumnVisibilityData->key,
                DynamicColumnVisibility::isShow => $dynamicColumnVisibilityData->is_show,
                DynamicColumnVisibility::addedUserId => Auth::id(),
            ]
        );

        return $dynamicColumnVisibility;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

}
