<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Item
        DB::table('psx_core_menus')->insert([
            'module_name' => 'item',
            'module_desc' => 'Items List',
            'module_lang_key' => 'item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'item_custom_field',
            'module_desc' => 'Create Custom Field',
            'module_lang_key' => 'item_custom_field_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'currency',
            'module_desc' => 'Items Currency',
            'module_lang_key' => 'currency_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'slow_moving_item',
            'module_desc' => 'Slow Moving Item',
            'module_lang_key' => 'slow_moving_item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // Category
        DB::table('psx_core_menus')->insert([
            'module_name' => 'category',
            'module_desc' => 'Category',
            'module_lang_key' => 'category_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'subcategory',
            'module_desc' => 'Subcategory',
            'module_lang_key' => 'subcategory_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Location
        DB::table('psx_core_menus')->insert([
            'module_name' => 'city',
            'module_desc' => 'Location City',
            'module_lang_key' => 'city_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '4',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'township',
            'module_desc' => 'Location Township',
            'module_lang_key' => 'township_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '4',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Shop
        DB::table('psx_core_menus')->insert([
            'module_name' => 'shop',
            'module_desc' => 'Shop',
            'module_lang_key' => 'shop_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'shipping',
            'module_desc' => 'Shipping',
            'module_lang_key' => 'shipping_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'transaction_status',
            'module_desc' => 'Transaction Status',
            'module_lang_key' => 'transaction_status_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'payment_status',
            'module_desc' => 'Payment Status',
            'module_lang_key' => 'payment_status_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Extension
        DB::table('psx_core_menus')->insert([
            'module_name' => 'core_key_type',
            'module_desc' => 'Type',
            'module_lang_key' => 'core_key_type_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '7',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'payment',
            'module_desc' => 'Payment',
            'module_lang_key' => 'payment_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '7',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // Approval
        DB::table('psx_core_menus')->insert([
            'module_name' => 'pending_item',
            'module_desc' => 'Pending Items',
            'module_lang_key' => 'pending_item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'bluemarkuser',
            'module_desc' => 'Blue Mark User',
            'module_lang_key' => 'bluemarkuser_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'disable_item',
            'module_desc' => 'Disable Items',
            'module_lang_key' => 'disable_item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'reject_item',
            'module_desc' => 'Reject Items',
            'module_lang_key' => 'reject_item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'offline_paid_item',
            'module_desc' => 'Offline Sponsored Item',
            'module_lang_key' => 'offline_paid_item_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'offline_package',
            'module_desc' => 'Offline Package',
            'module_lang_key' => 'offline_package_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // Report
        DB::table('psx_core_menus')->insert([
            'module_name' => 'transaction',
            'module_desc' => 'Transaction History',
            'module_lang_key' => 'transaction_history_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'buyer_report',
            'module_desc' => 'Buyer Report',
            'module_lang_key' => 'buyer_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'seller_report',
            'module_desc' => 'Seller Report',
            'module_lang_key' => 'seller_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'user_report',
            'module_desc' => 'User Report',
            'module_lang_key' => 'user_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'daily_active_user_report',
            'module_desc' => 'Daily Active User Report',
            'module_lang_key' => 'daily_active_user_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'successful_deal_count_report',
            'module_desc' => 'Successful Deal Count Report',
            'module_lang_key' => 'successful_deal_count_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'sold_out_item_report',
            'module_desc' => 'Sold Out Item Report',
            'module_lang_key' => 'sold_out_item_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'item_report',
            'module_desc' => 'Item Report',
            'module_lang_key' => 'item_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'paid_item',
            'module_desc' => 'Promotion Report',
            'module_lang_key' => 'promotion_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'category_report',
            'module_desc' => 'category Report',
            'module_lang_key' => 'category_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'complaint_item_report',
            'module_desc' => 'Complaint Item Report',
            'module_lang_key' => 'complaint_item_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'package_report',
            'module_desc' => 'Package Report',
            'module_lang_key' => 'package_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'contact',
            'module_desc' => 'Contact Us Message',
            'module_lang_key' => 'contact_us_message_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'slow_moving_item_report',
            'module_desc' => 'Slow Moving Item Report',
            'module_lang_key' => 'slow_moving_item_report_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '8',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // User Management
        DB::table('psx_core_menus')->insert([
            'module_name' => 'user',
            'module_desc' => 'User',
            'module_lang_key' => 'user_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '9',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'banned_user',
            'module_desc' => 'Banned User',
            'module_lang_key' => 'banned_user_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '9',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'user_role',
            'module_desc' => 'Role',
            'module_lang_key' => 'user_role_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '9',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // User Feedback
        DB::table('psx_core_menus')->insert([
            'module_name' => 'favourite',
            'module_desc' => 'Favourite',
            'module_lang_key' => 'favourite_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '10',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Configuration Setting
        DB::table('psx_core_menus')->insert([
            'module_name' => 'backend_setting',
            'module_desc' => 'Backend Setting',
            'module_lang_key' => 'backend_setting_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '12',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'mobile_setting',
            'module_desc' => 'Mobile Setting',
            'module_lang_key' => 'mobile_setting_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '12',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'frontend_setting',
            'module_desc' => 'Frontend Setting',
            'module_lang_key' => 'frontend_setting_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '12',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'system_config',
            'module_desc' => 'System Configuration',
            'module_lang_key' => 'system_config_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '12',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Menu Setting
        DB::table('psx_core_menus')->insert([
            'module_name' => 'menu_group',
            'module_desc' => 'Menu Gruop',
            'module_lang_key' => 'menu_group_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '13',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'sub_menu_group',
            'module_desc' => 'Sub Menu Gruop',
            'module_lang_key' => 'sub_menu_group_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '13',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'module',
            'module_desc' => 'Module',
            'module_lang_key' => 'module_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '13',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Miscellaneous
        DB::table('psx_core_menus')->insert([
            'module_name' => 'deeplink_generator',
            'module_desc' => 'Deeplink Generator',
            'module_lang_key' => 'deeplink_generator_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'push_notification_message',
            'module_desc' => 'Push Notification',
            'module_lang_key' => 'push_notification_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'demo_data_deletion',
            'module_desc' => 'Demo Data Deletion',
            'module_lang_key' => 'demo_data_deletion_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'language',
            'module_desc' => 'Language',
            'module_lang_key' => 'language_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'mobile_language',
            'module_desc' => 'Mobile Lanaguage',
            'module_lang_key' => 'mobile_language_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'about',
            'module_desc' => 'App About',
            'module_lang_key' => 'about_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'privacy_policy',
            'module_desc' => 'Privacy Policy',
            'module_lang_key' => 'privacy_policy_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'data_deletion_policy',
            'module_desc' => 'Data Deletion Policy',
            'module_lang_key' => 'data_deletion_policy_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'api_token',
            'module_desc' => 'API Tokens',
            'module_lang_key' => 'api_tokens_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'thumbnail_generator',
            'module_desc' => 'Thumbnail Generator',
            'module_lang_key' => 'thumbnail_generator_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'image_lists',
            'module_desc' => 'Image Lists',
            'module_lang_key' => 'image_lists_module',
            'ordering' => '1',
            'is_show_on_menu' => '0',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // Payment
        DB::table('psx_core_menus')->insert([
            'module_name' => 'payment_setting',
            'module_desc' => 'Payment Setting',
            'module_lang_key' => 'payment_setting_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '11',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'offline_payment_setting',
            'module_desc' => 'Offline Payment Setting',
            'module_lang_key' => 'offline_payment_setting_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '11',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'promotion_in_app_purchase',
            'module_desc' => 'Promotion InApp Purchase Setting',
            'module_lang_key' => 'promotion_in_app_purchase_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '11',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'package_in_app_purchase',
            'module_desc' => 'Package InApp Purchase Setting',
            'module_lang_key' => 'package_in_app_purchase_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '11',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'available_currency',
            'module_desc' => 'Payment Currency',
            'module_lang_key' => 'payment_currency_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '11',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_menus')->insert([
            'module_name' => 'phone_country_code',
            'module_desc' => 'Phone Country Code',
            'module_lang_key' => 'phone_country_code_module',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_sub_menu_group_id' => '14',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
