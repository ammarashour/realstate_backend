<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CoreKeyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_key_types')->insert([
            'code' => 'uit',
            'name' => 'UI Type',
            'description' => 'UI Type Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'uit',
            'counter' => 11,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_types')->insert([
            'code' => 'prd',
            'name' => 'Product',
            'description' => 'Product Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'prd',
            'counter' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_types')->insert([
            'code' => 'itm',
            'name' => 'Item',
            'description' => 'Item Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'itm',
            'counter' => 9,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_types')->insert([
            'code' => 'usr',
            'name' => 'User',
            'description' => 'User Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'usr',
            'counter' => 6,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_types')->insert([
            'code' => 'pmt',
            'name' => 'Payment',
            'description' => 'Payment Desc',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'pmt',
            'counter' => 25,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_core_key_counters')->insert([
            'code' => 'payment',
            'counter' => 7,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
