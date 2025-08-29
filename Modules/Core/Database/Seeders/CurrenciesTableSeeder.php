<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_currencies')->insert([
            'currency_short_form' => 'USD',
            'currency_symbol' => '$',
            'status' => 1,
            'is_default' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_currencies')->insert([
            'currency_short_form' => 'GBP',
            'currency_symbol' => '£',
            'status' => 1,
            'is_default' => '0',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_currencies')->insert([
            'currency_short_form' => 'JPY',
            'currency_symbol' => '¥',
            'status' => 1,
            'is_default' => '0',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_currencies')->insert([
            'currency_short_form' => 'PKR',
            'currency_symbol' => '₨',
            'status' => 1,
            'is_default' => '0',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_currencies')->insert([
            'currency_short_form' => 'SGD',
            'currency_symbol' => '$',
            'status' => 1,
            'is_default' => '0',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
