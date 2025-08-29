<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MobileLanaguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_mobile_languages')->insert([
            'symbol' => 'en',
            'language_code' => 'en',
            'country_code' => 'US',
            'name' => 'English',
            'code' => '1658679951199',
            'status' => '1',
            'enable' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_mobile_languages')->insert([
            'symbol' => 'ar',
            'language_code' => 'ar',
            'country_code' => 'DZ',
            'name' => 'Arabic',
            'code' => '1661660608474',
            'status' => '0',
            'enable' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_mobile_languages')->insert([
            'symbol' => 'es',
            'language_code' => 'es',
            'country_code' => 'ES',
            'name' => 'Spanish',
            'code' => '1661660608415',
            'status' => '0',
            'enable' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_mobile_languages')->insert([
            'symbol' => 'fr',
            'language_code' => 'fr',
            'country_code' => 'FR',
            'name' => 'French',
            'code' => '1661660608225',
            'status' => '0',
            'enable' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
