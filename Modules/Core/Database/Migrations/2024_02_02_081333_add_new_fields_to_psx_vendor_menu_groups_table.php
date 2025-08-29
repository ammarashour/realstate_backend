<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\VendorMenu;
use Modules\Core\Entities\Menu\VendorMenuGroup;
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
                'id' => 'ps-0000000010',
                'title' => 'core__vendor_order_transaction_module',
                'lang_key' => 'core__vendor_order_transaction_module',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
                'updated_date' => Carbon::now(),
                'is_not_from_sidebar' => "0",
                'status' => '1',
                'route_name' => 'order_transaction_report.index'
            ]);

            $ownerRolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, 1)->first();
            $ownerPermission = json_decode($ownerRolePermissions->module_and_permission);
            $ownerPermission->{'ps-0000000010'} = ["1", "2", "3", "4"];
            $ownerRolePermissions->module_and_permission = json_encode($ownerPermission);
            $ownerRolePermissions->update();

            $otherMenuGroup = new VendorMenuGroup();
            // $otherMenuGroup->id = 5;
            $otherMenuGroup->group_name = 'Report';
            $otherMenuGroup->group_icon = '';
            $otherMenuGroup->group_lang_key = 'core__vendor_report_label';
            $otherMenuGroup->is_show_on_menu = 1;
            $otherMenuGroup->ordering = 3;
            $otherMenuGroup->is_invisible_group_name = 0;
            $otherMenuGroup->added_user_id = 1;
            $otherMenuGroup->save();

            $statusManagementIcon = DB::table('psx_icons')->insertGetId([
                'icon_name' => 'transactionReport',
                'added_date' => Carbon::now(),
                'added_user_id' => '1',
            ]);

            $subMenu = new VendorSubMenuGroup();
            // $subMenu->id = 5;
            $subMenu->sub_menu_name = 'Transaction Reports';
            $subMenu->sub_menu_desc = 'Transaction Reports';
            $subMenu->icon_id = $statusManagementIcon;
            $subMenu->sub_menu_lang_key = 'core__vendor_transaction_reports_module';
            $subMenu->ordering = 1;
            $subMenu->is_show_on_menu = 1;
            $subMenu->core_menu_group_id  = $otherMenuGroup->id;
            $subMenu->is_dropdown  = 1;
            $subMenu->added_user_id = 1;
            $subMenu->save();

            $paymentStatusMenu = new VendorMenu();
            // $paymentStatusMenu->id = 6;
            $paymentStatusMenu->module_name = 'vendor_order_transaction';
            $paymentStatusMenu->module_desc = 'Order Transaction';
            $paymentStatusMenu->module_lang_key = 'core__vendor_order_transaction_module';
            $paymentStatusMenu->ordering = 1;
            $paymentStatusMenu->is_show_on_menu = 1;
            $paymentStatusMenu->module_id = 'ps-0000000010';
            $paymentStatusMenu->core_sub_menu_group_id = $subMenu->id;
            $paymentStatusMenu->added_user_id = 1;
            $paymentStatusMenu->save();

            DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000010')->update(['menu_id' => $paymentStatusMenu->id]);
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
