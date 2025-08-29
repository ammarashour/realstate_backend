<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;

class CustomFieldService extends PsService implements CustomFieldServiceInterface
{
    public function __construct() {}

    public function save($customFieldData)
    {
        $customField = new CustomField();
        if(!empty($customFieldData->id)){
            $customField->id  = $customFieldData->id;
        }
        $customField->category_id = $customFieldData->category_id;
        $customField->table_id  = $customFieldData->table_id;
        $customField->project_name = $customFieldData->project_name;
        $customField->project_id = $customFieldData->project_id;
        $customField->name = $customFieldData->name;
        $customField->placeholder = $customFieldData->placeholder;
        $customField->ui_type_id = $customFieldData->ui_type_id;
        $customField->core_keys_id = $customFieldData->core_keys_id;
        $customField->is_delete = $customFieldData->is_delete;
        $customField->data_type = $customFieldData->data_type;
        $customField->module_name = $customFieldData->module_name;
        $customField->base_module_name = $customFieldData->base_module_name;
        $customField->enable = $customFieldData->enable;
        $customField->mandatory = $customFieldData->mandatory;
        $customField->is_show_sorting = $customFieldData->is_show_sorting;
        $customField->ordering = $customFieldData->ordering;
        $customField->is_show_in_filter = $customFieldData->is_show_in_filter;
        $customField->is_include_in_hideshow = $customFieldData->is_include_in_hideshow;
        $customField->is_show = $customFieldData->is_show;
        $customField->is_core_field = 0;
        $customField->permission_for_enable_disable = $customFieldData->permission_for_enable_disable;
        $customField->permission_for_delete = $customFieldData->permission_for_delete;
        $customField->permission_for_mandatory = $customFieldData->permission_for_mandatory;
        $customField->added_user_id  = Auth::id();
        $customField->save();

        return $customField;
    }

    public function update($id, $customFieldData)
    {
        $customField = $this->get($id);
        $customField->updated_user_id  = Auth::id();
        $customField->update($customFieldData);

        return $customField;
    }

    public function deleteAll($isByTruncate = null)
    {
        if(!empty($isByTruncate)){
            CustomField::truncate();
        }
    }

    public function delete($id)
    {
        // soft delete
        $customField = $this->get($id);
        $customField->is_delete = Constants::delete;
        $customField->update();

        $name = $customField->name;

        return $name;
    }

    public function get($id = null, $tableId = null, $relation = null, $coreKeysId = null, $code = null)
    {
        $customField = CustomField::when($id, function ($q, $id) {
            $q->where(CustomField::id, $id);
        })
            ->when($code, function ($q, $code) {
                $q->where(CustomField::moduleName, $code);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($tableId, function ($q, $tableId) {
                $q->where(CustomField::tableId, $tableId);
            })
            ->when($coreKeysId, function ($q, $coreKeysId) {
                $q->where(CustomField::coreKeysId, $coreKeysId);
            })
            ->first();

        return $customField;
    }

    public function getAll($relation = null, $tableId = null, $withNoPag = null, $tableIds = null, $coreKeysIds = null, $sort = null, $order = null, $search = null, $row = null, $isDelete = null, $ids = null, $code = null, $notStartWithAtCoreKeysIdCol = null, $isCoreField = null, $moduleName = null, $isLatest = null, $uiTypeId = null, $categoryId = null, $limit = null, $offset = null, $categoryIdOnly = false)
    {

        $customFields = CustomField::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($categoryId !== null, function ($q) use ($categoryId, $categoryIdOnly) {
                if ($categoryId == "0") {
                    $q->where(CustomField::categoryId, null);
                } else if($categoryIdOnly) {
                    $q->where(CustomField::categoryId, $categoryId);
                } else {
                    $q->where(function ($query) use ($categoryId) {
                        $query->where(CustomField::categoryId, $categoryId)
                            ->orWhere(CustomField::categoryId, null);
                    });
                }
            })
            ->when($categoryId == null, function($q) {
                $q->where(CustomField::categoryId, null);
            })
            ->when($code, function ($q, $code) {
                $q->where(CustomField::moduleName, $code);
            })
            ->when($notStartWithAtCoreKeysIdCol, function ($q, $notStartWithAtCoreKeysIdCol) {
                $q->where(CustomField::coreKeysId, 'NOT LIKE', $notStartWithAtCoreKeysIdCol.'%');
            })
            ->when($moduleName, function ($q, $moduleName) {
                $q->where(CustomField::moduleName, $moduleName);
            })
            ->when($tableIds, function ($q, $tableIds) {
                $q->whereIn(CustomField::tableId, $tableIds);
            })
            ->when($coreKeysIds, function ($q, $coreKeysIds) {
                $q->whereIn(CustomField::coreKeysId, $coreKeysIds);
            })
            ->when($search, function ($query, $search) {
                $query->where(CustomField::name, 'like', '%'.$search.'%');
            })
            ->when($tableId, function ($q, $tableId) {
                $q->where(CustomField::tableId, $tableId);
            })
            ->when($uiTypeId, function ($q, $uiTypeId) {
                $q->where(CustomField::uiTypeId, $uiTypeId);
            })
            ->when($sort && $sort !== 'ui_type_id', function ($q) use ($sort, $order) {

                $q->orderBy($sort, $order);
            })
            ->when($ids, function ($q, $ids) {
                $q->whereIn('id', $ids);
            })
            ->when($isDelete !== null, function ($q) use ($isDelete) {
                if ($isDelete !== null) {
                    $q->where(CustomField::isDelete, $isDelete);
                }
            })
            ->when($isCoreField !== null, function ($q) use ($isCoreField) {
                if ($isCoreField !== null) {
                    $q->where(CustomField::isCoreField, $isCoreField);
                }
            })
            ->when($isLatest, function ($q, $isLatest) {
                $q->latest();
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            });
        if ($withNoPag) {
            $customFields = $customFields->get();
        } else {
            $customFields = $customFields->paginate($row)->withQueryString();
        }

        return $customFields;
    }

    /**
     * ** Available Filter Params **
     * $filters = [
     *     CustomField::isDelete => null,
     *     CustomField::coreKeysId => null,
     *     CustomField::moduleName => null
     * ];
     */
    // public function getCustomizeFields($relations = [], $filters = [], $limit = null, $offset = null ) {
    //     $result = CustomField::query()
    //                 ->withRelations($relations)
    //                 ->categoryFilter($filters[CustomField::categoryId] ?? null)
    //                 ->filterByFields($filters)
    //                 ->limitAndOffset($limit, $offset)
    //                 ->get();
    //     return $result;
    // }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

}
