<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Utilities\UiType;

class ItemInfo extends Model
{
    use HasFactory;

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    protected $fillable = ['id', 'item_id', 'core_keys_id', 'value', 'ui_type_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_item_infos';

    const itemId = 'item_id';

    const id = 'id';

    const coreKeysId = 'core_keys_id';

    const value = 'value';

    const uiTypeId = 'ui_type_id';

    const tableName = 'psx_item_infos';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\ItemInfoFactory::new();
    }

    public static function t($key)
    {
        return ItemInfo::tableName . '.' . $key;
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function customizeUiDetail()
    {
        return $this->hasMany(CustomFieldAttribute::class, 'id', 'value');
        // ->select('id', 'name'); // For Next Update
    }

    public function uiType()
    {
        return $this->belongsTo(UiType::class, 'ui_type_id', 'core_keys_id');
        // ->select('id', 'name', 'core_keys_id'); // For next update
    }

    public function customizeUi()
    {
        return $this->belongsTo(CustomField::class, 'core_keys_id', 'core_keys_id');
        // ->select('id', 'name', 'placeholder', 'ui_type_id', 'core_keys_id', // For Next Update
        //     'mandatory',
        //     'is_show_sorting',
        //     'is_show_in_filter',
        //     'ordering',
        //     'enable',
        //     'is_delete',
        //     'module_name',
        //     'data_type',
        //     'base_module_name',
        //     'is_include_in_hideshow',
        //     'is_show',
        //     'is_core_field',
        //     'permission_for_enable_disable',
        //     'permission_for_delete',
        //     'permission_for_mandatory',
        //     'category_id');

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
     * @todo to fix this
     */
    // public function toArray()
    // {
    //     $data = null;
    //     if (!empty($this->value)) {
    //         if ($this->ui_type_id == 'uit00001') {
    //             $data  = CustomFieldAttribute::where("id", $this->value)->first();
    //         } else if ($this->ui_type_id == 'uit00003') {
    //             $data  = CustomFieldAttribute::where("id", $this->value)->first();
    //         }
    //     }
    //     return parent::toArray() + [
    //         "customizeUiDetail" => $data
    //     ];
    // }

}
