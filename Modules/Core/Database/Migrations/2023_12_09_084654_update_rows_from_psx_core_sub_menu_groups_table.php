<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Entities\Menu\Module;

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
            $vendorMenu = CoreSubMenuGroup::where('sub_menu_name', 'vendor_panel')->first();
            if ($vendorMenu) {
                $vendorMenu->sub_menu_name = 'vendor_menu';
                $vendorMenu->sub_menu_desc = 'Vendor Menu';
                $vendorMenu->sub_menu_lang_key = 'vendor_menu_module';
                $vendorMenu->ordering = 3;
                $vendorMenu->core_menu_group_id = 5;
                $vendorMenu->icon_id = 13;

                $vendorMenu->update();
            }

            $roleMenu = CoreMenu::where('module_id', 81)->first();
            if ($roleMenu) {
                $roleMenu->delete();
            }

            $roleSubMenu = CoreSubMenuGroup::where('module_id', 81)->first();

            if (!$roleSubMenu) {
                $roleSubMenu = new CoreSubMenuGroup();
                $roleSubMenu->sub_menu_name = 'vendor_role';
                $roleSubMenu->sub_menu_desc = 'Vendor Role';
                $roleSubMenu->icon_id = 9;
                $roleSubMenu->sub_menu_lang_key = 'vendor_role_module';
                $roleSubMenu->ordering = 4;
                $roleSubMenu->is_show_on_menu = 1;
                $roleSubMenu->core_menu_group_id = 5;
                $roleSubMenu->added_user_id = 1;
                $roleSubMenu->is_dropdown = 0;
                $roleSubMenu->module_id = 81;
                $roleSubMenu->save();
            }

            $roleModule = Module::where('id', 81)->first();
            $roleModule->menu_id = null;
            $roleModule->sub_menu_id = $roleSubMenu->id;
            $roleModule->update();
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
        Schema::table('', function (Blueprint $table) {});
    }
};
