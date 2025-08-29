<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

use Modules\Core\Entities\Menu\VendorMenuGroup;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use Modules\Core\Entities\Menu\VendorModule;
use Modules\Core\Entities\Menu\VendorMenu;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::beginTransaction();
        try {
            DB::table('psx_vendor_roles')->truncate();
            DB::table('psx_vendor_role_permissions')->truncate();
            DB::table('psx_vendor_modules')->truncate();
            // DB::table('psx_vendor_user_permissions')->truncate();
            VendorMenuGroup::truncate();
            VendorSubMenuGroup::truncate();
            VendorMenu::truncate();

            DB::table('psx_vendor_roles')->insert([
                'id' => 1,
                'name' => 'Owner',
                'description' => 'Owner Role',
                'status' => 1,
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);
            DB::table('psx_vendor_roles')->insert([
                'id' => 2,
                'name' => 'Manager',
                'description' => 'Manager Role',
                'status' => 1,
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);
            DB::table('psx_vendor_roles')->insert([
                'id' => 3,
                'name' => 'Employer',
                'description' => 'Employer Role',
                'status' => 1,
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);

            $allPermission = new \stdClass();
            $allPermission->{'ps-0000000001'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000002'} = ["1", "2", "3", "4"];

            $employerPermission = new \stdClass();
            $employerPermission->{'ps-0000000001'} = ["1", "2", "3", "4"];
            $employerPermission->{'ps-0000000002'} = ["1", "2", "3"];

            DB::table('psx_vendor_role_permissions')->insert([
                'id' => 1,
                'vendor_role_id' => 1,
                'module_and_permission' => json_encode($allPermission),
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);
            DB::table('psx_vendor_role_permissions')->insert([
                'id' => 2,
                'vendor_role_id' => 2,
                'module_and_permission' => json_encode($allPermission),
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);
            DB::table('psx_vendor_role_permissions')->insert([
                'id' => 3,
                'vendor_role_id' => 3,
                'module_and_permission' => json_encode($employerPermission),
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);

            DB::table('psx_vendor_modules')->insert([
                'id' => 'ps-0000000001',
                'title' => 'core__vendor_my_store_module',
                'lang_key' => 'core__vendor_my_store_module',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'vendor_info.index'
            ]);

            DB::table('psx_vendor_modules')->insert([
                'id' => 'ps-0000000002',
                'title' => 'core__vendor_my_store_module',
                'lang_key' => 'core__vendor_my_store_module',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'vendor_item.index'
            ]);

            $dashboardMenuGroup = new VendorMenuGroup();
            $dashboardMenuGroup->group_name = 'Dashboard';
            $dashboardMenuGroup->group_icon = '';
            $dashboardMenuGroup->group_lang_key = 'core__vendor_dashboard_label';
            $dashboardMenuGroup->is_show_on_menu = 1;
            $dashboardMenuGroup->ordering = 1;
            $dashboardMenuGroup->is_invisible_group_name = 1;
            $dashboardMenuGroup->added_user_id = 1;
            $dashboardMenuGroup->save();

            $productMenuGroup = new VendorMenuGroup();
            $productMenuGroup->group_name = 'Product Management';
            $productMenuGroup->group_icon = '';
            $productMenuGroup->group_lang_key = 'core__vendor_product_management';
            $productMenuGroup->is_show_on_menu = 1;
            $productMenuGroup->ordering = 2;
            $productMenuGroup->is_invisible_group_name = 0;
            $productMenuGroup->added_user_id = 1;
            $productMenuGroup->save();

            $myStoreIconId = DB::table('psx_icons')->insertGetId([
                'icon_name' => 'myStore',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
            ]);

            $soteSubMenu = new VendorSubMenuGroup();
            $soteSubMenu->sub_menu_name = 'My Store';
            $soteSubMenu->sub_menu_desc = 'My Store';
            $soteSubMenu->icon_id = $myStoreIconId;
            $soteSubMenu->sub_menu_lang_key = 'core__vendor_my_store_module';
            $soteSubMenu->ordering = 1;
            $soteSubMenu->is_show_on_menu = 1;
            $soteSubMenu->core_menu_group_id  = $dashboardMenuGroup->id;
            $soteSubMenu->is_dropdown  = 0;
            $soteSubMenu->module_id = 'ps-0000000001';
            $soteSubMenu->added_user_id = 1;
            $soteSubMenu->save();

            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000001')->update(['sub_menu_id' => $soteSubMenu->id]);

            $subMenu = new VendorSubMenuGroup();
            $subMenu->sub_menu_name = 'Items';
            $subMenu->sub_menu_desc = 'Items';
            $subMenu->icon_id = 2;
            $subMenu->sub_menu_lang_key = 'core__vendor_item_module';
            $subMenu->ordering = 2;
            $subMenu->is_show_on_menu = 1;
            $subMenu->core_menu_group_id  = $productMenuGroup->id;
            $subMenu->is_dropdown  = 1;
            $subMenu->added_user_id = 1;
            $subMenu->save();

            $menu = new VendorMenu();
            $menu->module_name = 'Item List';
            $menu->module_desc = 'Item List';
            $menu->module_lang_key = 'core__vendor_my_store_module';
            $menu->ordering = 1;
            $menu->is_show_on_menu = 1;
            $menu->module_id = 'ps-0000000002';
            $menu->core_sub_menu_group_id = $subMenu->id;
            $menu->added_user_id = 1;
            $menu->save();

            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000002')->update(['menu_id' => $menu->id]);
        } catch (Exception $_) {
        }
        // DB::commit();
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
