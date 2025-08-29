<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Database\factories\Menu\CoreMenuGroupFactory;
use Modules\Core\Http\Facades\RolePermissionFacade;
use Modules\Core\Http\Facades\UserPermissionFacade;

class CoreMenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'group_name', 'group_icon', 'group_lang_key', 'is_show_on_menu', 'is_invisible_group_name', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_core_menu_groups";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const groupName = "group_name";
    const ordering = "ordering";
    const tableName = "psx_core_menu_groups";
    const id = 'id';
    const isShowOnMenu = "is_show_on_menu";

    protected static function newFactory()
    {
        return CoreMenuGroupFactory::new();
    }

    public function sub_menu_group(){
        // $loginUserRoles = UserPermission::where('user_id', Auth::id())->first();
        $loginUserRoles = UserPermissionFacade::get(userId: Auth::id());

        if (!empty($loginUserRoles)) {
            $roleIds = explode(',', $loginUserRoles->role_id);
            // $moduleIds = RolePermission::whereIn('role_id', $roleIds)->get()->pluck("module_id");
            $moduleIds = RolePermissionFacade::getAll(roleIds: $roleIds, moduleId: null, pagPerPage: null, noPagination: Constants::yes)->pluck("module_id");
            $dropDownSubMenuIds = CoreSubMenuGroup::where("is_dropdown", 1)->get()->pluck("id");
            $linkSubMenuIds = Module::whereIn('id', $moduleIds)->where('sub_menu_id', '!=', 0)->get()->pluck("sub_menu_id");
            $subMenuIds = $dropDownSubMenuIds->merge($linkSubMenuIds);
            return $this->hasMany(CoreSubMenuGroup::class)->where('is_show_on_menu', 1)->with(['module', 'icon', 'routeName'])->whereIn('id', $subMenuIds)->orderBy('ordering', 'asc');
        } else {
            return $this->hasMany(CoreSubMenuGroup::class)->where('is_show_on_menu', 1)->with(['module', 'icon', 'routeName'])->orderBy('ordering', 'asc');
        }
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
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
