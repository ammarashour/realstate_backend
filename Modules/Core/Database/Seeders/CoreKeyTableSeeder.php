<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CoreKeyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00001',
            'name' => 'Dropdown',
            'description' => 'Dropdown Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00002',
            'name' => 'Text',
            'description' => 'Text Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00003',
            'name' => 'Radio',
            'description' => 'Radio Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00004',
            'name' => 'Checkbox',
            'description' => 'Checkbox Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00005',
            'name' => 'DateTime',
            'description' => 'DateTime Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00006',
            'name' => 'Textarea',
            'description' => 'Textarea Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00007',
            'name' => 'number',
            'description' => 'number Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00008',
            'name' => 'MultiSelect',
            'description' => 'MultiSelect Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00009',
            'name' => 'Image',
            'description' => 'Image Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00010',
            'name' => 'TimeOnly',
            'description' => 'TimeOnly Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'uit00011',
            'name' => 'DateOnly',
            'description' => 'DateOnly Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        //-------------------------------
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00001',
            'name' => 'Item Type',
            'description' => 'Item Type Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00002',
            'name' => 'Item Price',
            'description' => 'Item Price Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00003',
            'name' => 'Item Condition',
            'description' => 'Item Condition Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00004',
            'name' => 'Deal Option Remark',
            'description' => 'Deal Option Remark Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00005',
            'name' => 'Highlight Info',
            'description' => 'Highlight Info Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00006',
            'name' => 'Deal Option',
            'description' => 'Deal Option Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00007',
            'name' => 'Brand',
            'description' => 'Brand Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00008',
            'name' => 'Business Mode',
            'description' => 'Business Mode Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'itm00009',
            'name' => 'Address',
            'description' => 'Address Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        //---------------------
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00001',
            'name' => 'Paypal Merchant Id',
            'description' => 'Paypal Merchant Id',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00002',
            'name' => 'Paypal Public Key',
            'description' => 'Paypal Public Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00003',
            'name' => 'Paypal Private Key',
            'description' => 'Paypal Private Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00004',
            'name' => 'Paypal Client Id',
            'description' => 'Paypal Client Id',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00005',
            'name' => 'Paypal Secret Key',
            'description' => 'Paypal Secret Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00006',
            'name' => 'Paypal Environment',
            'description' => 'Paypal Environment',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00012',
            'name' => 'Stripe Publishable Key',
            'description' => 'Stripe Publishable Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00013',
            'name' => 'Stripe Secret Key',
            'description' => 'Stripe Secret Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00014',
            'name' => 'Razor Key',
            'description' => 'Razor Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00015',
            'name' => 'Paystack Key',
            'description' => 'Paystack Key',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        //--------------------
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00001',
            'name' => 'city',
            'description' => 'City',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00002',
            'name' => 'is_verify_blue_mark',
            'description' => 'Is Verify Blue Mark',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00003',
            'name' => 'blue_mark_note',
            'description' => 'Blue Mark Note',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00004',
            'name' => 'remaining_post',
            'description' => 'Remaining Post',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00005',
            'name' => 'follower_count',
            'description' => 'Follower Count',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'usr00006',
            'name' => 'following_count',
            'description' => 'Following Count',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
