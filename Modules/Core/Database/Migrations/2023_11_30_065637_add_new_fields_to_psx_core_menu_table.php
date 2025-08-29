<?php

use Carbon\Carbon;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
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
            $coreSubMenuGroup = new CoreSubMenuGroup();
            $coreSubMenuGroup->sub_menu_name = 'vendor_approval';
            $coreSubMenuGroup->sub_menu_desc = 'Vendor Approval';
            $coreSubMenuGroup->icon_id = 1;
            $coreSubMenuGroup->sub_menu_lang_key = 'vendor_approval';
            $coreSubMenuGroup->ordering = 1;
            $coreSubMenuGroup->is_show_on_menu = 1;
            $coreSubMenuGroup->core_menu_group_id = 5;
            $coreSubMenuGroup->added_user_id = 1;
            $coreSubMenuGroup->is_dropdown = 1;
            $coreSubMenuGroup->save();

            $module = new Module();
            $module->id = 83;
            $module->title = 'pending_vendor';
            $module->lang_key = 'pending_vendor_module';
            $module->added_user_id = 1;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'pending_vendor.index';
            $module->save();

            DB::table('psx_core_sub_menu_groups')->where('id', '=', $coreSubMenuGroup->id)->update(['module_id' => $module->id]);

            $coreMenu = new CoreMenu();
            $coreMenu->module_name = 'pending_vendor';
            $coreMenu->module_desc = 'Pending Vendors';
            $coreMenu->module_lang_key = 'pending_vendor_module';
            $coreMenu->ordering = 2;
            $coreMenu->is_show_on_menu = 1;
            $coreMenu->module_id = $module->id;
            $coreMenu->core_sub_menu_group_id = $coreSubMenuGroup->id;
            $coreMenu->added_user_id = 1;
            $coreMenu->save();

            DB::table('psx_modules')->where('id', '=', $module->id)->update(['menu_id' => $coreMenu->id]);

            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => $module->id,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
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
        Schema::table('psx_core_menu', function (Blueprint $table) {});
    }
};
