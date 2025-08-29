<?php

use Carbon\Carbon;
use Modules\Core\Entities\Icon;
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
            $coreMenuGroup = new CoreMenuGroup();
            $coreMenuGroup->id = 5;
            $coreMenuGroup->group_name = 'Vendor';
            $coreMenuGroup->group_icon = 'vendor';
            $coreMenuGroup->group_lang_key = 'vendor_group';
            $coreMenuGroup->is_show_on_menu = 1;
            $coreMenuGroup->ordering = 3;
            $coreMenuGroup->is_invisible_group_name = 0;
            $coreMenuGroup->added_user_id = 1;
            $coreMenuGroup->save();

            DB::table('psx_core_menu_groups')->where('id', '=', 2)->update(['ordering' => 4]);
            DB::table('psx_core_menu_groups')->where('id', '=', 3)->update(['ordering' => 5]);

            $icon = new Icon();
            $icon->id = 17;
            $icon->icon_name = 'vendor';
            $icon->added_user_id = 1;
            $icon->save();

            $coreSubMenuGroup = new CoreSubMenuGroup();
            $coreSubMenuGroup->sub_menu_name = 'vendor_list';
            $coreSubMenuGroup->sub_menu_desc = 'Vendor List';
            $coreSubMenuGroup->icon_id = $icon->id;
            $coreSubMenuGroup->sub_menu_lang_key = 'vendor_list';
            $coreSubMenuGroup->ordering = 2;
            $coreSubMenuGroup->is_show_on_menu = 1;
            $coreSubMenuGroup->core_menu_group_id = $coreMenuGroup->id;
            $coreSubMenuGroup->added_user_id = 1;
            $coreSubMenuGroup->is_dropdown = 1;
            $coreSubMenuGroup->save();

            $module = new Module();
            $module->id = 82;
            $module->title = 'vendor';
            $module->lang_key = 'vendor_list_module';
            $module->added_user_id = 1;
            $module->sub_menu_id = $coreSubMenuGroup->id;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'vendor.index';
            $module->save();

            DB::table('psx_core_sub_menu_groups')->where('id', '=', $coreSubMenuGroup->id)->update(['module_id' => $module->id]);

            $coreMenu = new CoreMenu();
            $coreMenu->module_name = 'vendor';
            $coreMenu->module_desc = 'Vendor List';
            $coreMenu->module_lang_key = 'vendor_list_module';
            $coreMenu->ordering = 1;
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
        Schema::table('psx_core_menu_groups', function (Blueprint $table) {});
    }
};
