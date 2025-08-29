<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use Modules\Core\Entities\Icon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Auth;
// use Modules\Core\Entities\VendorUserPermission;
// use Modules\Core\Entities\VendorRolePermission;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorSubMenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'sub_menu_name', 'sub_menu_desc', 'sub_menu_lang_key', 'icon_id','is_show_on_menu','ordering','core_menu_group_id','is_dropdown', 'module_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_sub_menus";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const subMenuName = "sub_menu_name";
    const subMenuDesc = "sub_menu_desc";
    const subMenuLangKey = "sub_menu_lang_key";
    const iconId = "icon_id";
    const isShowOnMenu = "is_show_on_menu";
    const ordering = "ordering";
    const coreMenuGroupId = "core_menu_group_id";
    const moduleId = "module_id";
    const isDropdown = "is_dropdown";
    const tableName = "psx_vendor_sub_menus";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\LanguageStringFactory::new();
    }

    public static function t($key)
    {
        return VendorSubMenuGroup::tableName.".".$key;
    }

    public function module(){
        return $this->hasMany(VendorMenu::class, 'core_sub_menu_group_id')->where('is_show_on_menu', 1)->orderBy('ordering', 'asc')->with(['routeName']);
    }

    public function core_menu_group(){
        return $this->belongsTo(VendorMenuGroup::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function icon(){
        return $this->belongsTo(Icon::class,'icon_id','id');
    }

    public function routeName(){
        return $this->belongsTo(VendorModule::class, "module_id", "id");
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
