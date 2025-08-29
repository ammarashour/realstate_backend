<?php

use Modules\Core\Entities\Menu\VendorMenu;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Modules\Core\Entities\Menu\VendorModule;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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

            $allPermission = new \stdClass();
            $allPermission->{'ps-0000000001'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000002'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000003'} = ["1", "2", "3", "4"];

            $employerPermission = new \stdClass();
            $employerPermission->{'ps-0000000001'} = ["1", "2", "3", "4"];
            $employerPermission->{'ps-0000000002'} = ["1", "2", "3"];
            $employerPermission->{'ps-0000000002'} = ["1", "2", "3", "4"];

            DB::table('psx_vendor_role_permissions')->where("id", 1)->update([

                'vendor_role_id' => 1,
                'module_and_permission' => json_encode($allPermission),
                'added_user_id' => 1,
                'added_date' => Carbon::now(),
            ]);


            DB::table('psx_vendor_modules')->insert([
                'id' => 'ps-0000000003',
                'title' => 'core__vendor_subscription_hist',
                'lang_key' => 'core__vendor_subscription_hist',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'vendor_subscription_history.index'
            ]);


            $productMenuGroup = new VendorMenuGroup();
            $productMenuGroup->group_name = 'Packages';
            $productMenuGroup->group_icon = '';
            $productMenuGroup->group_lang_key = 'core__vendor_packages';
            $productMenuGroup->is_show_on_menu = 1;
            $productMenuGroup->ordering = 2;
            $productMenuGroup->is_invisible_group_name = 0;
            $productMenuGroup->added_user_id = 1;
            $productMenuGroup->save();



            // DB::table('psx_vendor_modules')->where('id','=','ps-0000000001')->update(['sub_menu_id' => $soteSubMenu->id]);

            $subMenu = new VendorSubMenuGroup();
            $subMenu->sub_menu_name = 'Subscriptions';
            $subMenu->sub_menu_desc = 'Subscriptions';
            $subMenu->icon_id = 19;
            $subMenu->sub_menu_lang_key = 'core__vendor_subscriptions';
            $subMenu->ordering = 2;
            $subMenu->is_show_on_menu = 1;
            $subMenu->core_menu_group_id  = $productMenuGroup->id;
            $subMenu->is_dropdown  = 1;
            $subMenu->added_user_id = 1;
            $subMenu->save();

            $menu = new VendorMenu();
            $menu->module_name = 'vendor_subscription_history';
            $menu->module_desc = 'Subscription History';
            $menu->module_lang_key = 'core__vendor_subscription_hist';
            $menu->ordering = 1;
            $menu->is_show_on_menu = 1;
            $menu->module_id = 'ps-0000000003';
            $menu->core_sub_menu_group_id = $subMenu->id;
            $menu->added_user_id = 1;
            $menu->save();

            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000003')->update(['menu_id' => $menu->id]);
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
        Schema::table('psx_vendor_menus', function (Blueprint $table) {});
    }
};
