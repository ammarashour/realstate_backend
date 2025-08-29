<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add new payment
        DB::table('psx_payments')->insert([
            'id' => 'payment00010',
            'name' => 'Flutterwave',
            'description' => 'Flutterwave',
            'status' => '1',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);

        // add core keys
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'ps-pmt00044',
            'name' => 'Flutterwave Public Key',
            'description' => 'Flutterwave Public Key',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'ps-pmt00045',
            'name' => 'Flutterwave Secret Key',
            'description' => 'Flutterwave Secret Key',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_core_keys')->insert([
            'core_keys_id' => 'ps-pmt00046',
            'name' => 'Flutterwave Encryption Key',
            'description' => 'Flutterwave Encryption Key',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);

        // update core key counter
        DB::table('psx_core_key_counters')->insert([
            'code' => 'ps-pmt',
            'counter' => 46,
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_core_key_counters')->insert([
            'code' => 'payment',
            'counter' => 10,
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);

        // add core key payment relation
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00044',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00045',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_core_key_payment_relations')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00046',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);

        // add payment info
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00044',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00045',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
        DB::table('psx_payment_infos')->insert([
            'payment_id' => 'payment00010',
            'core_keys_id' => 'ps-pmt00046',
            'added_date' => Carbon::now(),
            'added_user_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_payments', function (Blueprint $table) {

        });
    }
};
