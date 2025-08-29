<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CoreFieldFilterSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'title',
            'label_name' => 'Product Name',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'category_id@@name',
            'label_name' => 'Category',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'subcategory_id@@name',
            'label_name' => 'Sub Category',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'city_id@@name',
            'label_name' => 'City',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'township_id@@name',
            'label_name' => 'Township',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'description',
            'label_name' => 'Description',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'dynamic_link',
            'label_name' => 'Deeplink',
            'data_type' => 'Integer',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'price',
            'label_name' => 'Price',
            'data_type' => 'Integer',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'added_user_id@@name',
            'label_name' => 'Owner',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'currency_id@@currency_short_form',
            'label_name' => 'Currency',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'itm',
            'field_name' => 'shop_id@@name',
            'label_name' => 'Shop',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        //-------------------------
        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'name',
            'label_name' => 'Name',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'email',
            'label_name' => 'Email',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'user_phone',
            'label_name' => 'Phone',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'role_id@@name',
            'label_name' => 'User Role',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'user_address',
            'label_name' => 'Address',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'user_about_me',
            'label_name' => 'About Me',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'overall_rating',
            'label_name' => 'Overall Raing',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'user_lat',
            'label_name' => 'User Lat',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'user_lng',
            'label_name' => 'User Lng',
            'data_type' => 'String',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'added_date',
            'label_name' => 'Added Date',
            'data_type' => 'Date',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'status',
            'label_name' => 'Publish',
            'data_type' => 'Integer',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_field_filter_settings')->insert([
            'module_name' => 'usr',
            'field_name' => 'is_banned',
            'label_name' => 'Ban',
            'data_type' => 'Integer',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

    }
}
