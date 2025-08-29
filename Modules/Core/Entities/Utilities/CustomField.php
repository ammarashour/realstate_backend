<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CustomFieldAttribute as EntitiesCustomizeUiDetail;
use Modules\Core\Entities\ProductInfo;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Entities\Utilities\UiType;

class CustomField extends PsModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'placeholder',
        'ui_type_id',
        'core_keys_id',
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
        'category_id',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag',
    ];

    const tableName = "psx_customize_ui";
    const id = "id";
    const name = "name";
    const tableId = "table_id";
    const placeholder = "placeholder";
    const uiTypeId = "ui_type_id";
    const coreKeysId = "core_keys_id";
    const mandatory = "mandatory";
    const isShowSorting = "is_show_sorting";
    const isShowInFilter = 'is_show_in_filter';
    const ordering = 'ordering';
    const enable = "enable";
    const isDelete = "is_delete";
    const isCoreField = "is_core_field";
    const moduleName = "module_name";
    const dataType = 'data_type';
    const projectId = 'project_id';
    const projectName = 'project_name';
    const baseModuleName = 'base_module_name';
    const isIncludeInHideShow = 'is_include_in_hideshow';
    const isShow = 'is_show';
    const permissionForEnableDisable = 'permission_for_enable_disable';
    const permissionForDelete = 'permission_for_delete';
    const permissionForMandatory = 'permission_for_mandatory';
    const categoryId = "category_id";
    const addedDate = "added_date";
    const addedUserId = "added_user_id";
    const updatedDate = "updated_date";
    const updatedUserId = "updated_user_id";
    const updatedFlag = "updated_flag";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $table = 'psx_customize_ui';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Utilities\CustomFieldFactory::new();
    }

    public static function t($key)
    {
        return CustomField::tableName . "." . $key;
    }

    public function hideShowSettingForFields()
    {
        return $this->hasOne(DynamicColumnVisibility::class, "key", "core_keys_id");
    }

    public function customizeUiDetail()
    {
        return $this->hasMany(CustomFieldAttribute::class, 'core_keys_id', 'core_keys_id');
    }

    public function coreKeysId()
    {
        return $this->hasOne(ProductInfo::class, "core_keys_id", "core_keys_id");
    }

    public function uiTypeId()
    {
        return $this->belongsTo(UiType::class, "ui_type_id", "core_keys_id");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    /**
     * @todo we should not override like this
     */
    // public function toArray()
    // {
    //     if ($this->ui_type_id == 'uit00003') {
    //         $data  = EntitiesCustomizeUiDetail::where("core_keys_id",$this->core_keys_id)->get();
    //     }else {
    //         $data  = [];
    //     }
    //     return parent::toArray() + [
    //         "customizeUiDetails" => $data
    //     ];
    // }


    public function getCustomizeUiSelectSQL()
    {
        $sqlParts = Cache::remember('abc', 100, function () {
            $customizeUiArr = CustomField::where(CustomField::moduleName, 'itm')->latest()->get();
            $customizeUiDeatilArr = [];
            $sqlParts = [];

            // Collect unique custom UI details for specific types
            foreach ($customizeUiArr as $customizeUiDetail) {
                if (in_array($customizeUiDetail->ui_type_id, [Constants::dropDownUi, Constants::radioUi, Constants::multiSelectUi])) {
                    $customizeUiDeatilArr[$customizeUiDetail->core_keys_id . '@@name'] = $customizeUiDetail->core_keys_id;
                }
            }

            // Add SQL parts for the custom UI details
            foreach (array_unique($customizeUiDeatilArr) as $key => $customizeuideatil) {
                $sqlParts[] = "MAX(CASE WHEN psx_item_infos.core_keys_id = '$customizeuideatil' THEN psx_customize_ui_details.name END) AS '$key'";
            }

            // Add SQL parts for the core keys
            foreach ($customizeUiArr as $customizeUi) {
                $sqlParts[] = "MAX(CASE WHEN psx_item_infos.core_keys_id = '$customizeUi->core_keys_id' THEN psx_item_infos.value END) AS '$customizeUi->core_keys_id'";
            }

            return $sqlParts;
        });
        return implode(', ', $sqlParts);
    }

    ////////////////////////////////////////////////////////////////////
    /// Scope Functions
    ////////////////////////////////////////////////////////////////////

    // Relations
    public function scopeWithRelations($query, $relations)
    {
        if (!empty($relations)) {
            $query->with($relations);
        }
        return $query;
    }

    // Filters
    public function scopeCategoryFilter($query, $categoryId)
    {

        if (!empty($categoryId)) {
            if ($categoryId == "0") {
                $query->where(CustomField::categoryId, null);
            } else {
                $query->where(CustomField::categoryId, $categoryId)->orWhere(CustomField::categoryId, null);
            }
        }
        return $query;
    }

    public function scopeFilterByFields($query, $keyList)
    {
        $supportedFields = [
            CustomField::coreKeysId,
            CustomField::isDelete,
            CustomField::moduleName
        ];

        if (!empty($keyList) && count($keyList) > 0) {

            foreach ($keyList as $key => $value) {
                if (in_array($key, $supportedFields)) {
                    if ($value !== null) {
                        $query->where($key, $value);
                    }
                }
            }
        }
        return $query;
    }

    // limit and offset
    public function scopeLimitAndOffset($query, $limit = null, $offset = null)
    {

        if (!empty($limit)) {
            $query->limit($limit);
        }

        if (!empty($offset)) {
            $query->offset($offset);
        }

        return $query;
    }


    ////////////////////////////////////////////////////////////////////
    /// End Scope Functions
    ////////////////////////////////////////////////////////////////////

}
