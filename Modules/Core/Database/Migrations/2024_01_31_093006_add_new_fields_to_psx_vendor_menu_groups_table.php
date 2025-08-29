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
                    'id' => 'ps-0000000007',
                    'title' => 'core__vendor_payment_status_module',
                    'lang_key' => 'core__vendor_payment_status_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_payment_status.index'
                ]);

                DB::table('psx_vendor_modules')->insert([
                    'id' => 'ps-0000000008',
                    'title' => 'core__vendor_order_status_module',
                    'lang_key' => 'core__vendor_order_status_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_order_status.index'
                ]);

                DB::table('psx_vendor_modules')->insert([
                    'id' => 'ps-0000000009',
                    'title' => 'core__vendor_order_list_module',
                    'lang_key' => 'core__vendor_order_list_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_order_list.index'
                ]);

                $ownerRolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, 1)->first();
                $ownerPermission = json_decode($ownerRolePermissions->module_and_permission);
                $ownerPermission->{'ps-0000000007'} = ["1", "2", "3", "4"];
                $ownerPermission->{'ps-0000000008'} = ["1", "2", "3", "4"];
                $ownerPermission->{'ps-0000000009'} = ["1", "2", "3", "4"];
                $ownerRolePermissions->module_and_permission = json_encode($ownerPermission);
                $ownerRolePermissions->update();

                DB::table('psx_vendor_menu_groups')->where('id', '=', '2')->update(['ordering' => '3']);

                $otherMenuGroup = new VendorMenuGroup();
                $otherMenuGroup->id = 5;
                $otherMenuGroup->group_name = 'Other';
                $otherMenuGroup->group_icon = '';
                $otherMenuGroup->group_lang_key = 'core__vendor_other_label';
                $otherMenuGroup->is_show_on_menu = 1;
                $otherMenuGroup->ordering = 5;
                $otherMenuGroup->is_invisible_group_name = 0;
                $otherMenuGroup->added_user_id = 1;
                $otherMenuGroup->save();

                $orderManagementMenuGroup = new VendorMenuGroup();
                $orderManagementMenuGroup->id = 6;
                $orderManagementMenuGroup->group_name = 'Order Management';
                $orderManagementMenuGroup->group_icon = '';
                $orderManagementMenuGroup->group_lang_key = 'core__vendor_order_management_label';
                $orderManagementMenuGroup->is_show_on_menu = 1;
                $orderManagementMenuGroup->ordering = 2;
                $orderManagementMenuGroup->is_invisible_group_name = 0;
                $orderManagementMenuGroup->added_user_id = 1;
                $orderManagementMenuGroup->save();

                $statusManagementIcon = DB::table('psx_icons')->insertGetId([
                    'icon_name' => 'statusManagement',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                ]);

                $orderManagementIcon = DB::table('psx_icons')->insertGetId([
                    'icon_name' => 'order',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                ]);

                $subMenu = new VendorSubMenuGroup();
                $subMenu->id = 5;
                $subMenu->sub_menu_name = 'Status Management';
                $subMenu->sub_menu_desc = 'Status Management';
                $subMenu->icon_id = $statusManagementIcon;
                $subMenu->sub_menu_lang_key = 'core__vendor_status_management_module';
                $subMenu->ordering = 1;
                $subMenu->is_show_on_menu = 1;
                $subMenu->core_menu_group_id  = $otherMenuGroup->id;
                $subMenu->is_dropdown  = 1;
                $subMenu->added_user_id = 1;
                $subMenu->save();

                $orderSubMenu = new VendorSubMenuGroup();
                $orderSubMenu->id = 6;
                $orderSubMenu->sub_menu_name = 'Order';
                $orderSubMenu->sub_menu_desc = 'Order';
                $orderSubMenu->icon_id = $orderManagementIcon;
                $orderSubMenu->sub_menu_lang_key = 'core__vendor_order_module';
                $orderSubMenu->ordering = 1;
                $orderSubMenu->is_show_on_menu = 1;
                $orderSubMenu->core_menu_group_id  = $orderManagementMenuGroup->id;
                $orderSubMenu->is_dropdown  = 1;
                $orderSubMenu->added_user_id = 1;
                $orderSubMenu->save();

                $paymentStatusMenu = new VendorMenu();
                $paymentStatusMenu->id = 6;
                $paymentStatusMenu->module_name = 'vendor_payment_status';
                $paymentStatusMenu->module_desc = 'Payment Status';
                $paymentStatusMenu->module_lang_key = 'core__vendor_payment_status_module';
                $paymentStatusMenu->ordering = 1;
                $paymentStatusMenu->is_show_on_menu = 1;
                $paymentStatusMenu->module_id = 'ps-0000000007';
                $paymentStatusMenu->core_sub_menu_group_id = $subMenu->id;
                $paymentStatusMenu->added_user_id = 1;
                $paymentStatusMenu->save();

                $orderStatusMenu = new VendorMenu();
                $orderStatusMenu->id = 7;
                $orderStatusMenu->module_name = 'vendor_order_status';
                $orderStatusMenu->module_desc = 'Order Status';
                $orderStatusMenu->module_lang_key = 'core__vendor_order_status_module';
                $orderStatusMenu->ordering = 2;
                $orderStatusMenu->is_show_on_menu = 1;
                $orderStatusMenu->module_id = 'ps-0000000008';
                $orderStatusMenu->core_sub_menu_group_id = $subMenu->id;
                $orderStatusMenu->added_user_id = 1;
                $orderStatusMenu->save();

                $orderListMenu = new VendorMenu();
                $orderListMenu->id = 8;
                $orderListMenu->module_name = 'vendor_order_list';
                $orderListMenu->module_desc = 'Order List';
                $orderListMenu->module_lang_key = 'core__vendor_order_list_module';
                $orderListMenu->ordering = 2;
                $orderListMenu->is_show_on_menu = 1;
                $orderListMenu->module_id = 'ps-0000000009';
                $orderListMenu->core_sub_menu_group_id = $orderSubMenu->id;
                $orderListMenu->added_user_id = 1;
                $orderListMenu->save();

                DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000007')->update(['menu_id' => $paymentStatusMenu->id]);
                DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000008')->update(['menu_id' => $orderStatusMenu->id]);
                DB::table('psx_vendor_modules')->where('id', '=', 'ps-0000000009')->update(['menu_id' => $orderListMenu->id]);
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
