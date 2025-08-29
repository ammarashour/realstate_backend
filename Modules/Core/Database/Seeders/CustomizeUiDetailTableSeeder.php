<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CustomizeUiDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00001',
            'name' => 'Buy',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00001',
            'name' => 'Exchange',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00001',
            'name' => 'Lost and Found',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00001',
            'name' => 'Sell',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00003',
            'name' => 'Used',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00003',
            'name' => 'New',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00006',
            'name' => 'Meet Up',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00006',
            'name' => 'Mailing Or Delivery',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'Fixed',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'Negotiable',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'Price On Call',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'Auction',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'No Price',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_customize_ui_details')->insert([
            'core_keys_id' => 'ps-itm00002',
            'name' => 'Free',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
