<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use App\Config\ps_constant;
use Modules\Core\Entities\Icon;
use Modules\Core\Entities\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Core\Http\Facades\RolePermissionFacade;
use Modules\Core\Http\Facades\UserPermissionFacade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Database\factories\Menu\CoreMenuGroupsFactory;
use Modules\Core\Http\Facades\MobileSettingFacade;
use Modules\Core\Http\Facades\SystemConfigFacade;

class CoreSubMenuGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'sub_menu_name', 'sub_menu_desc', 'icon_id', 'sub_menu_icon', 'sub_menu_lang_key', 'module_id', 'ordering', 'is_show_on_menu', 'core_menu_group_id', 'is_dropdown', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_core_sub_menu_groups";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const tableName = "psx_core_sub_menu_groups";
    const subMenuDesc = "sub_menu_desc";
    const coreMenuGroupId = "core_menu_group_id";
    const isShowOnMenu = "is_show_on_menu";
    const subMenuName = "sub_menu_name";
    const isDropdown = "is_dropdown";
    const iconId = "icon_id";
    const ordering = "ordering";
    const moduleId = "module_id";

    protected static function newFactory()
    {
        return CoreMenuGroupsFactory::new();
    }

    public function module(){
        // $loginUserRoles = UserPermission::where('user_id', Auth::id())->first();
        $loginUserRoles = UserPermissionFacade::get(userId: Auth::id());
        $project = Project::select("base_project_id")->first();
        $baseProjectIdsToHideVendor = ps_constant::baseProjectIdsToHideVendor;
//        return $this->hasMany(CoreModule::class)->where('is_show_on_menu', 1)->orderBy('ordering', 'asc');

        if (!empty($loginUserRoles)) {
            $roleIds = explode(',', $loginUserRoles->role_id);
            // $coreModuleIds = RolePermission::whereIn('role_id', $roleIds)->get()->pluck("module_id");
            $coreModuleIds = RolePermissionFacade::getAll(roleIds: $roleIds, moduleId: null, pagPerPage: null, noPagination: Constants::yes)->pluck("module_id");
            $moduleIds = Module::whereIn('id', $coreModuleIds)->where('menu_id', '!=', 0)->get()->pluck("menu_id")->toArray();

            // hide subcateogry and township
            if(MobileSettingFacade::get()->is_show_subcategory == 0) {
                $moduleIds = array_diff($moduleIds, array(Constants::subCategoryModule));
            }
            if(SystemConfigFacade::get()->is_sub_location == 0) {
                $moduleIds = array_diff($moduleIds, array(Constants::locationTownshipModule));
            }

            return $this->hasMany(CoreMenu::class)->where(function($q) use ($project, $baseProjectIdsToHideVendor){
                if(in_array($project->base_project_id, $baseProjectIdsToHideVendor)){
                    $q->whereNotIn("id", ps_constant::vendorMenuIdsInAdminPanel);
                }
            })->where('is_show_on_menu', 1)->whereIn('id',$moduleIds)->orderBy('ordering', 'asc')->with(['routeName']);
        } else {
            return $this->hasMany(CoreMenu::class)->where(function($q) use ($project, $baseProjectIdsToHideVendor){
                if(in_array($project->base_project_id, $baseProjectIdsToHideVendor)){
                    $q->whereNotIn("id", ps_constant::vendorMenuIdsInAdminPanel);
                }
            })->where('is_show_on_menu', 1)->orderBy('ordering', 'asc')->with(['routeName']);
        }

    }

    public function core_menu_group(){
        return $this->belongsTo(CoreMenuGroup::class);
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
        return $this->belongsTo(Module::class, "module_id", "id");
    }


    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

//    public function toArray()
//    {
//        return parent::toArray() + [
//            'authorizations' => $this->authorizations(['update','delete','create'])
//        ];
//    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
