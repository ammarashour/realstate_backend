<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\VendorMenu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Vendor\VendorRolePermission;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;

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
            DB::table('psx_vendor_modules')->insert([
                'id' => 'ps-0000000005',
                'title' => 'core__vendor_payment_lists_module',
                'lang_key' => 'core__vendor_payment_lists_module',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'vendor_payment.index'
            ]);

            DB::table('psx_vendor_modules')->insert([
                'id' => 'ps-0000000006',
                'title' => 'core__vendor_payment_settings_module',
                'lang_key' => 'core__vendor_payment_settings_module',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'vendor_payment_setting.index'
            ]);

            $ownerRolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, 1)->first();
            $ownerPermission = json_decode($ownerRolePermissions->module_and_permission);
            $ownerPermission->{'ps-0000000005'} = ["1", "2", "3", "4"];
            $ownerPermission->{'ps-0000000006'} = ["1", "2", "3", "4"];
            $ownerRolePermissions->module_and_permission = json_encode($ownerPermission);
            $ownerRolePermissions->update();

            $paymentMenuGroup = new VendorMenuGroup();
            $paymentMenuGroup->group_name = 'Payment Management';
            $paymentMenuGroup->group_icon = '';
            $paymentMenuGroup->group_lang_key = 'core__vendor_payment_management_label';
            $paymentMenuGroup->is_show_on_menu = 1;
            $paymentMenuGroup->ordering = 4;
            $paymentMenuGroup->is_invisible_group_name = 0;
            $paymentMenuGroup->added_user_id = 1;
            $paymentMenuGroup->save();

            $subMenu = new VendorSubMenuGroup();
            $subMenu->sub_menu_name = 'Payment';
            $subMenu->sub_menu_desc = 'Payment';
            $subMenu->icon_id = 11;
            $subMenu->sub_menu_lang_key = 'core__vendor_payment_module';
            $subMenu->ordering = 1;
            $subMenu->is_show_on_menu = 1;
            $subMenu->core_menu_group_id  = $paymentMenuGroup->id;
            $subMenu->is_dropdown  = 1;
            $subMenu->added_user_id = 1;
            $subMenu->save();

            $listMenu = new VendorMenu();
            $listMenu->module_name = 'vendor_payment';
            $listMenu->module_desc = 'Payment Lists';
            $listMenu->module_lang_key = 'core__vendor_payment_lists_module';
            $listMenu->ordering = 1;
            $listMenu->is_show_on_menu = 1;
            $listMenu->module_id = 'ps-0000000005';
            $listMenu->core_sub_menu_group_id = $subMenu->id;
            $listMenu->added_user_id = 1;
            $listMenu->save();

            $settingMenu = new VendorMenu();
            $settingMenu->module_name = 'vendor_payment_setting';
            $settingMenu->module_desc = 'Payment Settings';
            $settingMenu->module_lang_key = 'core__vendor_payment_settings_module';
            $settingMenu->ordering = 1;
            $settingMenu->is_show_on_menu = 1;
            $settingMenu->module_id = 'ps-0000000006';
            $settingMenu->core_sub_menu_group_id = $subMenu->id;
            $settingMenu->added_user_id = 1;
            $settingMenu->save();

            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000005')->update(['menu_id' => $listMenu->id]);
            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000006')->update(['menu_id' => $settingMenu->id]);
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
        Schema::table('psx_vendor_menu_groups', function (Blueprint $table) {});
    }
};
