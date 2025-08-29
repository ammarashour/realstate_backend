<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SubMenuGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'approval',
            'sub_menu_desc' => 'Approval',
            // 'sub_menu_icon' => 'checkCircle',
            'sub_menu_lang_key' => 'approval_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'items',
            'sub_menu_desc' => 'Items',
            'sub_menu_lang_key' => 'items_group',
            // 'sub_menu_icon' => 'elements',
            'ordering' => '2',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'categories',
            'sub_menu_desc' => 'Categories',
            'sub_menu_lang_key' => 'categories_group',
            // 'sub_menu_icon' => 'list-unorder',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'locations',
            'sub_menu_desc' => 'Locations',
            'sub_menu_lang_key' => 'locations_group',
            // 'sub_menu_icon' => 'mapView',
            'ordering' => '4',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'blog',
            'sub_menu_desc' => 'Blogs',
            // 'sub_menu_icon' => 'ticket',
            'sub_menu_lang_key' => 'blogs_group',
            'ordering' => '5',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'extensions',
            'sub_menu_desc' => 'Extensions',
            // 'sub_menu_icon' => 'fileUpload',
            'sub_menu_lang_key' => 'extensions_group',
            'ordering' => '8',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'reports',
            'sub_menu_desc' => 'Reports',
            // 'sub_menu_icon' => 'file',
            'sub_menu_lang_key' => 'reports_group',
            'ordering' => '9',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'users_management',
            'sub_menu_desc' => 'Users Management',
            // 'sub_menu_icon' => 'user-group',
            'sub_menu_lang_key' => 'users_management_group',
            'ordering' => '10',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'users_feedback',
            'sub_menu_desc' => 'User Feedback',
            // 'sub_menu_icon' => 'message',
            'sub_menu_lang_key' => 'users_feedback_group',
            'ordering' => '11',
            'is_show_on_menu' => '0',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'payment',
            'sub_menu_desc' => 'Payemnt',
            // 'sub_menu_icon' => 'wallet-plus',
            'sub_menu_lang_key' => 'payment_group',
            'ordering' => '12',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'configuration_setting',
            'sub_menu_desc' => 'Configuration Setting',
            // 'sub_menu_icon' => 'setting-config',
            'sub_menu_lang_key' => 'config_setting_group',
            'ordering' => '13',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'menu_setting',
            'sub_menu_desc' => 'Menu Setting',
            // 'sub_menu_icon' => 'element-2',
            'sub_menu_lang_key' => 'menu_setting_group',
            'ordering' => '14',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'miscellaneous',
            'sub_menu_desc' => 'Miscellaneous',
            // 'sub_menu_icon' => 'plus-circle',
            'sub_menu_lang_key' => 'miscellaneous_group',
            'ordering' => '15',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '2',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_sub_menu_groups')->insert([
            'sub_menu_name' => 'table',
            'sub_menu_desc' => 'Table Setting',
            // 'sub_menu_icon' => 'grid-2',
            'sub_menu_lang_key' => 'table_setting_group',
            'ordering' => '16',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '3',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


    }
}
