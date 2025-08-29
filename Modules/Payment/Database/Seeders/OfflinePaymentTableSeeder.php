<?php

namespace Modules\Payment\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfflinePaymentTableSeeder extends Seeder
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
            'core_keys_id' => 'pmt00022',
            'name' => 'ABC Bank',
            'description' => 'ABC Bank',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00022',
            'value' => 'Account No : 424789-4782479-3278724',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00022',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00023',
            'name' => 'Money Bank',
            'description' => 'Money Bank',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00023',
            'value' => 'Account No : 23424-4782479-23424',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00023',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00024',
            'name' => 'Asian Pay',
            'description' => 'Asian Pay',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00024',
            'value' => 'Email : teamps.is.cool@gmail.com',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00024',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'pmt00025',
            'name' => 'PS Pay',
            'description' => 'PS Pay',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00025',
            'value' => 'Phone No : +9593848494949',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00005',
            'core_keys_id' => 'pmt00025',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


    }
}
