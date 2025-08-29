<?php

namespace Modules\Payment\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromotionIAPTableSeeder extends Seeder
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
            'core_keys_id' => 'pmt00018',
            'name' => 'android.test.purchased',
            'description' => 'android.test.purchased',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00018',
            'value' => '',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00018',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00018',
            'attribute_key' => 'type',
            'attribute_value' => 'Android',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00018',
            'attribute_key' => 'days',
            'attribute_value' => '10',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);



        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00019',
            'name' => 'promo_7_day',
            'description' => 'promo_7_day',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00019',
            'value' => 'desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00019',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00019',
            'attribute_key' => 'type',
            'attribute_value' => 'IOS',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00019',
            'attribute_key' => 'days',
            'attribute_value' => '7',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);



        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00020',
            'name' => 'promo_7_day',
            'description' => 'promo_7_day',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00020',
            'value' => 'desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00020',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00020',
            'attribute_key' => 'type',
            'attribute_value' => 'Android',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00020',
            'attribute_key' => 'days',
            'attribute_value' => '7',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00021',
            'name' => 'promo_1_day',
            'description' => 'promo_1_day',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00021',
            'value' => 'desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00021',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00021',
            'attribute_key' => 'type',
            'attribute_value' => 'Android',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00006',
            'core_keys_id' => 'pmt00021',
            'attribute_key' => 'days',
            'attribute_value' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


    }
}
