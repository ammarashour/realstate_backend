<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;

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
            $vendor_menu_group_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_menu_group_module')->first();
            $vendor_sub_menu_group_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_sub_menu_group_module')->first();
            $vendor_menu_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_menu_module')->first();
            $vendor_module_registering_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_module_registering_module')->first();
            $vendor_role_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_role_module')->first();

            if (empty($vendor_menu_group_module)) {
                $vendor_menu_group_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 77,
                    'title' => 'vendor_menu_group',
                    'lang_key' => 'vendor_menu_group_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_menu_group.index'
                ]);
            } else {
                $vendor_menu_group_module_id = $vendor_menu_group_module->id;
            }
            if (empty($vendor_sub_menu_group_module)) {
                $vendor_sub_menu_group_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 78,
                    'title' => 'vendor_sub_menu_group',
                    'lang_key' => 'vendor_sub_menu_group_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_sub_menu_group.index'
                ]);
            } else {
                $vendor_sub_menu_group_module_id = $vendor_sub_menu_group_module->id;
            }
            if (empty($vendor_menu_module)) {
                $vendor_menu_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 79,
                    'title' => 'vendor_menu',
                    'lang_key' => 'vendor_menu_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_menu.index'
                ]);
            } else {
                $vendor_menu_module_id = $vendor_menu_module->id;
            }
            if (empty($vendor_module_registering_module)) {
                $vendor_module_registering_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 80,
                    'title' => 'vendor_module_registering',
                    'lang_key' => 'vendor_module_registering_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_module_registering.index'
                ]);
            } else {
                $vendor_module_registering_module_id = $vendor_module_registering_module->id;
            }
            if (empty($vendor_role_module)) {
                $vendor_role_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 81,
                    'title' => 'vendor_role',
                    'lang_key' => 'vendor_role_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_role.index'
                ]);
            } else {
                $vendor_role_module_id = $vendor_role_module->id;
            }

            $vendor_panel = CoreSubMenuGroup::where('sub_menu_lang_key', '=', 'vendor_panel_module')->first();
            $vendor_menu_group = CoreMenu::where('module_name', '=', 'vendor_menu_group')->first();
            $vendor_sub_menu_group = CoreMenu::where('module_name', '=', 'vendor_sub_menu_group')->first();
            $vendor_menu = CoreMenu::where('module_name', '=', 'vendor_menu')->first();
            $vendor_module_registering = CoreMenu::where('module_name', '=', 'vendor_module_registering')->first();
            $vendor_role = CoreMenu::where('module_name', '=', 'vendor_role')->first();

            if (empty($vendor_panel)) {
                $subMenu = new CoreSubMenuGroup();
                $subMenu->sub_menu_name = 'vendor_panel';
                $subMenu->sub_menu_desc = 'Vendor Panel';
                $subMenu->icon_id = 12;
                $subMenu->sub_menu_lang_key = 'vendor_panel_module';
                $subMenu->ordering = 15;
                $subMenu->is_show_on_menu = 1;
                $subMenu->core_menu_group_id  = 3;
                $subMenu->is_dropdown  = 1;
                $subMenu->added_user_id = 1;
                $subMenu->save();
            }
            if (empty($vendor_menu_group)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_menu_group';
                $menu->module_desc = 'Vendor Menu Group';
                $menu->module_lang_key = 'vendor_menu_group_module';
                $menu->ordering = 1;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_menu_group_module_id;
                $menu->core_sub_menu_group_id = $subMenu->id ?? $vendor_panel->id;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_menu_group_module_id)->update(['menu_id' => $menu->id]);
            }
            if (empty($vendor_sub_menu_group)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_sub_menu_group';
                $menu->module_desc = 'Vendor Sub Menu Group';
                $menu->module_lang_key = 'vendor_sub_menu_group_module';
                $menu->ordering = 2;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_sub_menu_group_module_id;
                $menu->core_sub_menu_group_id = $subMenu->id ?? $vendor_panel->id;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_sub_menu_group_module_id)->update(['menu_id' => $menu->id]);
            }
            if (empty($vendor_menu)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_menu';
                $menu->module_desc = 'Vendor Menu';
                $menu->module_lang_key = 'vendor_menu_module';
                $menu->ordering = 3;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_menu_module_id;
                $menu->core_sub_menu_group_id = $subMenu->id ?? $vendor_panel->id;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_menu_module_id)->update(['menu_id' => $menu->id]);
            }
            if (empty($vendor_module_registering)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_module_registering';
                $menu->module_desc = 'Vendor Module Registering';
                $menu->module_lang_key = 'vendor_module_registering_module';
                $menu->ordering = 4;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_module_registering_module_id;
                $menu->core_sub_menu_group_id = $subMenu->id ?? $vendor_panel->id;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_module_registering_module_id)->update(['menu_id' => $menu->id]);
            }
            if (empty($vendor_role)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_role';
                $menu->module_desc = 'Vendor Role';
                $menu->module_lang_key = 'vendor_role_module';
                $menu->ordering = 5;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_role_module_id;
                $menu->core_sub_menu_group_id = $subMenu->id ?? $vendor_panel->id;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_role_module_id)->update(['menu_id' => $menu->id]);
            }
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 77,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 78,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 79,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 80,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 81,
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
    public function down() {}
};
