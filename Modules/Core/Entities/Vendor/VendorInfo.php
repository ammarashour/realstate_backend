<?php

namespace Modules\Core\Entities\Vendor;

use App\Models\User;
use Modules\Core\Entities\Utilities\UiType;
use Modules\Core\Entities\Utilities\CustomField;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'vendor_id', 'core_keys_id', 'ui_type_id', 'value', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_vendor_infos';

    const tableName = 'psx_vendor_infos';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const vendorId = 'vendor_id';
    const coreKeyId = 'core_keys_id';
    const uiTypeId = 'ui_type_id';
    const value = 'value';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorInfoFactory::new();
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
