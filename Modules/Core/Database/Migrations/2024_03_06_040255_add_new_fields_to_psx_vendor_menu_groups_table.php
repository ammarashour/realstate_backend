<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        DB::table('psx_vendor_modules')->insert([
            'id' => 'ps-0000000011',
            'title' => 'core__vendor_currency_module',
            'lang_key' => 'core__vendor_currency_module',
            'added_date' => Carbon::now(),
            'added_user_id' => '1',
            'updated_date' => Carbon::now(),
            'is_not_from_sidebar' => "0",
            'status' => '1',
            'route_name' => 'vendor_currency.index'
        ]);

        $ownerRolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, 1)->first();
        $ownerPermission = json_decode($ownerRolePermissions->module_and_permission);
        $ownerPermission->{'ps-0000000011'} = ["1", "2", "3", "4"];
        $ownerRolePermissions->module_and_permission = json_encode($ownerPermission);
        $ownerRolePermissions->update();

        $vendorCurrencyIcon = DB::table('psx_icons')->insertGetId([
            'icon_name' => 'venodrCurrency',
            'added_date' => Carbon::now(),
            'added_user_id' => '1',
        ]);

        $subMenu = new VendorSubMenuGroup();
        $subMenu->id = 8;
        $subMenu->sub_menu_name = 'Vendor Currencies';
        $subMenu->sub_menu_desc = 'Vendor Currencies';
        $subMenu->icon_id = $vendorCurrencyIcon;
        $subMenu->sub_menu_lang_key = 'core__vendor_currency_module';
        $subMenu->ordering = 2;
        $subMenu->is_show_on_menu = 1;
        $subMenu->core_menu_group_id  = 5;
        $subMenu->module_id  = 'ps-0000000011';
        $subMenu->is_dropdown  = 0;
        $subMenu->added_user_id = 1;
        $subMenu->save();

        DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000011')->update(['sub_menu_id' => $subMenu->id]);
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
