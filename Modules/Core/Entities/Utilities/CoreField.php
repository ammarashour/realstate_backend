<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreField extends PsModel
{
    use HasFactory;

    protected $fillable = [
        'placeholder',
        'label_name',
        'field_name',
        'mandatory',
        'is_show_sorting',
        'is_show_in_filter',
        'ordering',
        'enable',
        'is_delete',
        'module_name',
        'data_type',
        'table_id',
        'project_id',
        'project_name',
        'base_module_name',
        'is_include_in_hideshow',
        'is_show',
        'is_core_field',
        'permission_for_enable_disable',
        'permission_for_delete',
        'permission_for_mandatory',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag',
    ];

    protected $table = "psx_core_field_filter_settings";

    const id = "id";
    const tableId = "table_id";
    const mandatory = "mandatory";
    const isShowSorting = "is_show_sorting";
    const isShowInFilter = "is_show_in_filter";
    const ordering = "ordering";
    const projectId = "project_id";
    const projectName = "project_name";
    const placeholder = "placeholder";
    const moduleName = "module_name";
    const baseModuleName = "base_module_name";
    const dataType = 'data_type';
    const fieldName = "field_name";
    const labelName = "label_name";
    const isDelete = "is_delete";
    const tableName = "psx_core_field_filter_settings";
    const enable = "enable";
    const isIncludeInHideShow = "is_include_in_hideshow";
    const isCoreField = "is_core_field";
    const isShow = "is_show";
    const permissionForEnableDisable = "permission_for_enable_disable";
    const permissionForDelete = "permission_for_delete";
    const permissionForMandatory = "permission_for_mandatory";
    const addedDate = "added_date";
    const addedUserId = "added_user_id";
    const updatedUserId = "updated_user_id";
    const updatedDate = "updated_date";
    const updatedFlag = "updated_flag";


    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $casts = [
        'enable' => 'integer',
        'is_delete' => 'integer',
    ];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Utilities\CoreFieldFactory::new();
    }

    public function screenDisplayUiSetting(){
        return $this->hasOne(DynamicColumnVisibility::class,"key","id");
    }

    public function toArray()
    {
        if (str_contains($this->field_name,"@@")) {
            $originFieldName = strstr($this->field_name,"@@",true);

        } else {
            $originFieldName = $this->field_name;
        }
        return parent::toArray() + [
            'original_field_name' => $originFieldName
        ];
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    // accessor
//    protected function fieldName(): Attribute
//    {
//        return Attribute::make(
//            get: function ($value){
//                if (str_contains($value,"@@")) {
//                    return strstr($value,"@@",true);
//                } else {
//                    return $value;
//                }
//            },
//        );
//    }

}
