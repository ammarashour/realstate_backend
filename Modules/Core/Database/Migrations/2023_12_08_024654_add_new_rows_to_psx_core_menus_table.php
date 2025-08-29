<?php

use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Menu\CoreMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Authorization\RolePermission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $module = new Module();
            $module->id = 84;
            $module->title = 'vendor_panel_setting';
            $module->lang_key = 'vendor_panel_setting';
            $module->added_user_id = 1;
            $module->sub_menu_id = 12;
            $module->status = 1;
            $module->route_name = 'vendor_setting.index';
            $module->save();

            $menu = new CoreMenu();
            $menu->module_name = 'vendor_panel_setting';
            $menu->module_desc = 'Vendor Panel Setting';
            $menu->module_lang_key = 'vendor_panel_setting_module';
            $menu->ordering = 3;
            $menu->is_show_on_menu = 1;
            $menu->module_id = $module->id;
            $menu->core_sub_menu_group_id = 12;
            $menu->added_user_id = 1;
            $menu->save();

            $addedModule = Module::find($module->id);
            $addedModule->menu_id = $menu->id;
            $addedModule->update();

            $rolePermission = new RolePermission();
            $rolePermission->role_id = 1;
            $rolePermission->module_id = $module->id;
            $rolePermission->permission_id = '1,2,3,4';
            $rolePermission->added_user_id = 1;
            $rolePermission->save();
        } catch (Exception $_) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_core_menus', function (Blueprint $table) {});
    }
};
