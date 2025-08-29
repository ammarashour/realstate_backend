<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicColumnVisibility extends PsModel
{
    use HasFactory;

    protected $fillable = ['id', 'module_name', 'key', 'is_show', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_screen_display_ui_settings';

    const id = 'id';

    const moduleName = 'module_name';

    const key = 'key';

    const isShow = 'is_show';

    const addedUserId = 'added_user_id';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Utilities\DynamicColumnVisibilityFactory::new();
    }

    public function coreField()
    {
        return $this->belongsTo(CoreField::class, 'key', 'field_name')->where('is_delete', 0);
    }

    public function customizeField()
    {
        return $this->belongsTo(CustomField::class, 'key', 'core_keys_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
