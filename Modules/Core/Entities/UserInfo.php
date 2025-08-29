<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Database\factories\UserInfoFactory;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Utilities\UiType;

class UserInfo extends Model
{
    use HasFactory;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_user_infos";
    const userId = "user_id";
    const id = "id";
    const coreKeysId = "core_keys_id";
    const value = "value";
    const uiTypeId = "ui_type_id";
    const updatedDate = "updated_date";

    protected $fillable = ['id', 'user_id', 'core_keys_id', 'value', 'ui_type_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_user_infos";

    protected static function newFactory()
    {
        return UserInfoFactory::new();
    }

    public static function t($key)
    {
        return UserInfo::tableName . "." . $key;
    }

    public function uiType()
    {
        return $this->belongsTo(UiType::class, "ui_type_id", "core_keys_id");
    }

    public function customizeUi()
    {
        return $this->belongsTo(CustomField::class, "core_keys_id", "core_keys_id");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function toArray()
    {
        $data = null;
        if (!empty($this->value)) {
            if ($this->ui_type_id == 'uit00001') {
                $data  = CustomFieldAttribute::where("id", $this->value)->first();
            } else if ($this->ui_type_id == 'uit00003') {
                $data  = CustomFieldAttribute::where("id", $this->value)->first();
            }
        }
        return parent::toArray() + [
            "customizeUiDetail" => $data
        ];
    }
}
