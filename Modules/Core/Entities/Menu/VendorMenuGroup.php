<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Vendor\VendorRolePermission;
use Modules\Core\Entities\Vendor\VendorUserPermission;
use Modules\Core\Entities\VendorModule;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;

class VendorMenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'group_name', 'group_lang_key', 'group_icon', 'is_show_on_menu', 'ordering', 'is_invisible_group_name', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_menu_groups";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const groupName = "group_name";
    const groupLangKey = "group_lang_key";
    const groupIcon = "group_icon";
    const isIncisibleGroupName = "is_invisible_group_name";
    const isShowOnMenu = "is_show_on_menu";
    const ordering = "ordering";
    const tableName = "psx_vendor_menu_groups";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\LanguageStringFactory::new();
    }

    public static function t($key)
    {
        return VendorMenuGroup::tableName . "." . $key;
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

    public function sub_menu_group()
    {
        return $this->hasMany(VendorSubMenuGroup::class, 'core_menu_group_id')->where('is_show_on_menu', 1)->with(['module', 'icon', 'routeName'])->orderBy('ordering', 'asc');
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
