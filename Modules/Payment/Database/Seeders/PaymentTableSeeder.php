<?php

namespace Modules\Payment\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_payments')->insert([
            'id' => 'payment00001',
            'name' => 'Pay Pal',
            'description' => 'Pay Pal',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00002',
            'name' => 'Stripe',
            'description' => 'Stripe',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00003',
            'name' => 'Razor',
            'description' => 'Razor',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00004',
            'name' => 'Paystack',
            'description' => 'Paystack',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00005',
            'name' => 'Offline Payment',
            'description' => 'Offline Payment',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00006',
            'name' => 'IAP Promote',
            'description' => 'In App Purchase For Promote',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_payments')->insert([
            'id' => 'payment00007',
            'name' => 'IAP Package',
            'description' => 'In App Purchase For Package',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
