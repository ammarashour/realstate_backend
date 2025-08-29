<?php

namespace Modules\Payment\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PackageIAPTableSeeder extends Seeder
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
            'core_keys_id' => 'pmt00016',
            'name' => 'android.test.purchased',
            'description' => 'android.test.purchased',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'value' => 'Silver',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'attribute_key' => 'type',
            'attribute_value' => 'Android',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'attribute_key' => 'count',
            'attribute_value' => '10',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'attribute_key' => 'price',
            'attribute_value' => '10',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'attribute_key' => 'status',
            'attribute_value' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00016',
            'attribute_key' => 'currency_id',
            'attribute_value' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00017',
            'name' => 'ios.test.purchased',
            'description' => 'ios.test.purchased',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'value' => 'Silver',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'attribute_key' => 'type',
            'attribute_value' => 'IOS',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'attribute_key' => 'count',
            'attribute_value' => '10',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'attribute_key' => 'price',
            'attribute_value' => '10',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'attribute_key' => 'status',
            'attribute_value' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_attributes')->insert([
            'payment_id' => 'payment00007',
            'core_keys_id' => 'pmt00017',
            'attribute_key' => 'currency_id',
            'attribute_value' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
