<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;

class CoreFieldService extends PsService implements CoreFieldServiceInterface
{
    public function __construct() {}

    public function save($coreFieldData)
    {
        $coreField = new CoreField();
        if(!empty($coreFieldData->id)){
            $coreField->id = $coreFieldData->id;
        }
        $coreField->table_id = $coreFieldData->table_id;
        $coreField->project_name = $coreFieldData->project_name;
        $coreField->project_id = $coreFieldData->project_id;
        $coreField->label_name = $coreFieldData->label_name;
        $coreField->module_name = $coreFieldData->module_name;
        $coreField->base_module_name = $coreFieldData->base_module_name;
        $coreField->field_name = $coreFieldData->field_name;
        $coreField->placeholder = $coreFieldData->placeholder;
        $coreField->data_type = $coreFieldData->data_type;
        $coreField->is_delete = $coreFieldData->is_delete;
        $coreField->enable = $coreFieldData->enable;
        $coreField->mandatory = $coreFieldData->mandatory;
        $coreField->is_show_sorting = $coreFieldData->is_show_sorting;
        $coreField->ordering = $coreFieldData->ordering;
        $coreField->is_show_in_filter = $coreFieldData->is_show_in_filter;
        $coreField->is_include_in_hideshow = $coreFieldData->is_include_in_hideshow;
        $coreField->is_show = $coreFieldData->is_show;
        $coreField->is_core_field = 1;
        $coreField->permission_for_enable_disable = $coreFieldData->permission_for_enable_disable;
        $coreField->permission_for_delete = $coreFieldData->permission_for_delete;
        $coreField->permission_for_mandatory = $coreFieldData->permission_for_mandatory;
        $coreField->added_user_id = Auth::id();
        $coreField->save();

        return $coreField;
    }

    public function update($id, $coreFieldData)
    {
        $coreField = $this->get($id);
        $coreField->updated_user_id  = Auth::id();
        $coreField->update($coreFieldData);

        return $coreField;
    }

    public function delete($id)
    {
        // soft delete
        $coreField = $this->get($id);
        $coreField->is_delete = Constants::delete;
        $coreField->update();

        $name = $coreField->label_name;

        return $name;
    }

    public function deleteAll($isByTruncate = null)
    {
        if(!empty($isByTruncate)){
            CoreField::truncate();
        }
    }

    public function get($id = null)
    {
        $coreField = CoreField::when($id, function ($q, $id) {
            $q->where(CoreField::id, $id);
        })
            ->first();

        return $coreField;
    }

    public function getAll($code = null, $relation = null, $limit = null, $offset = null, $isDel=null, $withNoPag = null, $pagPerPage = null, $projectId = null, $tableId = null, $conds = null, $notInFieldNames = null)
    {
        $coreFields = CoreField::when($code, function ($q, $code){
                                                $q->where(CoreField::moduleName, $code);
                                            })
                                            ->when($projectId, function ($q, $projectId){
                                                $q->where(CoreField::projectId, $projectId);
                                            })
                                            ->when($tableId, function ($q, $tableId){
                                                $q->where(CoreField::tableId, $tableId);
                                            })
                                            ->when($limit, function ($query,$limit){
                                                $query->limit($limit);
                                            })
                                            ->when($relation, function ($query, $relation){
                                                $query->with($relation);
                                            })
                                            ->when($conds, function ($query, $conds){
                                                $query->where($conds);
                                            })
                                            ->when($notInFieldNames, function ($query, $notInFieldNames){
                                                $query->whereNotIn(CoreField::fieldName, $notInFieldNames);
                                            })
                                            ->when($isDel !== null,  function ($query) use ($isDel){
                                                $query->where(CoreField::isDelete, $isDel);
                                            })
                                            ->when($offset, function ($query,$offset){
                                                $query->offset($offset);
                                            });
        if ($withNoPag) {
            $coreFields = $coreFields->get();
        } else {
            $coreFields = $coreFields->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        }

        return $coreFields;
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

}
