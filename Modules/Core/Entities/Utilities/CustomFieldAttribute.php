<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use App\Models\User;
use Modules\Core\Entities\Utilities\UiType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

class CustomFieldAttribute extends PsModel
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'core_keys_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_customize_ui_details";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const tableName = "psx_customize_ui_details";
    const name = "name";
    const coreKeysId = "core_keys_id";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Utilities\CustomFieldAttributeFactory::new();
    }

    public static function t($key)
    {
        return CustomFieldAttribute::tableName . "." . $key;
    }

    public function customize_ui()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function ui_type()
    {
        return $this->belongsTo(UiType::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
