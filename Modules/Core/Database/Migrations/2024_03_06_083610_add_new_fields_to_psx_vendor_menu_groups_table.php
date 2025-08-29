_<?php

    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Modules\Core\Entities\Menu\VendorMenu;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Modules\Core\Entities\Menu\VendorMenuGroup;
    use Illuminate\Database\Migrations\Migration;
    use Modules\Core\Entities\Menu\VendorSubMenuGroup;
    use Modules\Core\Entities\Vendor\VendorRolePermission;

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
                    'id' => 'ps-0000000012',
                    'title' => 'core__vendor_delivery_setting_module',
                    'lang_key' => 'core__vendor_delivery_setting_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_delivery_setting.index'
                ]);

                $ownerRolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, 1)->first();
                $ownerPermission = json_decode($ownerRolePermissions->module_and_permission);
                $ownerPermission->{'ps-0000000012'} = ["1", "2", "3", "4"];
                $ownerRolePermissions->module_and_permission = json_encode($ownerPermission);
                $ownerRolePermissions->update();

                $subMenu = new VendorSubMenuGroup();
                $subMenu->id = 9;
                $subMenu->sub_menu_name = 'Setting';
                $subMenu->sub_menu_desc = 'Setting';
                $subMenu->icon_id = 12;
                $subMenu->sub_menu_lang_key = 'core__vendor_panel_setting_module';
                $subMenu->ordering = 3;
                $subMenu->is_show_on_menu = 1;
                $subMenu->core_menu_group_id  = 5;
                $subMenu->is_dropdown  = 1;
                $subMenu->added_user_id = 1;
                $subMenu->save();

                $deliverySettingMenu = new VendorMenu();
                $deliverySettingMenu->id = 10;
                $deliverySettingMenu->module_name = 'vendor_delivery_setting';
                $deliverySettingMenu->module_desc = 'Delivery Setting';
                $deliverySettingMenu->module_lang_key = 'core__vendor_delivery_setting_module';
                $deliverySettingMenu->ordering = 1;
                $deliverySettingMenu->is_show_on_menu = 1;
                $deliverySettingMenu->module_id = 'ps-0000000012';
                $deliverySettingMenu->core_sub_menu_group_id = $subMenu->id;
                $deliverySettingMenu->added_user_id = 1;
                $deliverySettingMenu->save();

                DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000012')->update(['menu_id' => $deliverySettingMenu->id]);
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
