<?php

namespace Modules\Core\Entities\Menu;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use Modules\Core\Entities\Menu\VendorModule;

class VendorMenu extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'module_name', 'module_desc', 'module_lang_key', 'icon_id', 'is_show_on_menu', 'ordering', 'core_sub_menu_group_id', 'module_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_menus";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const moduleName = "module_name";
    const moduleDesc = "module_desc";
    const moduleLangKey = "module_lang_key";
    const iconId = "icon_id";
    const isShowOnMenu = "is_show_on_menu";
    const ordering = "ordering";
    const coreSubMenuGroupId = "core_sub_menu_group_id";
    const moduleId = "module_id";
    const tableName = "psx_vendor_menus";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\LanguageStringFactory::new();
    }
    public static function t($key)
    {
        return VendorMenu::tableName . "." . $key;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function core_sub_menu_group()
    {
        return $this->belongsTo(VendorSubMenuGroup::class);
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

    public function routeName()
    {
        return $this->belongsTo(VendorModule::class, "module_id", "id");
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
