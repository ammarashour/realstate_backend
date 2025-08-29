<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\VendorMenu;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Menu\VendorModule;
use Illuminate\Database\Schema\Blueprint;
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
            $module = new VendorModule();
            $module->id = 'ps-0000000004';
            $module->title = 'core__vendor_subscription_upgrade';
            $module->lang_key = 'core__vendor_subscription_upgrade';
            $module->added_user_id = 1;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'upgrade_subscription.index';
            $module->save();

            $menu = new VendorMenu();
            $menu->module_name = 'upgrade_subscription';
            $menu->module_desc = 'Upgrade Subscription';
            $menu->module_lang_key = 'core__vendor_subscription_upgrade';
            $menu->is_show_on_menu = 1;
            $menu->ordering = 2;
            $menu->core_sub_menu_group_id = 3;
            $menu->module_id = $module->id;
            $menu->added_user_id = 1;
            $menu->save();

            DB::table('psx_vendor_modules')->where('id', '=', $module->id)->update(['menu_id' => $menu->id]);

            $allPermission = new \stdClass();
            $allPermission->{'ps-0000000001'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000002'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000003'} = ["1", "2", "3", "4"];
            $allPermission->{'ps-0000000004'} = ["1", "2", "3", "4"];

            DB::table('psx_vendor_role_permissions')->where("id", 1)->update([
                'module_and_permission' => json_encode($allPermission),
                'updated_user_id' => 1,
                'updated_date' => Carbon::now(),
            ]);


            // update Item List of module_name
            $menu = VendorMenu::where("module_name", "item List")->first();
            $menu->module_name = "vendor_item";
            $menu->update();
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
        Schema::table('vendor_menus', function (Blueprint $table) {});
    }
};
