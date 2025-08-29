<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_languages')->insert([
            'symbol' => 'en',
            'name' => 'English',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'ar',
            'name' => 'Arabic',
            'status' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'fr',
            'name' => 'French',
            'status' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'es',
            'name' => 'Spanish',
            'status' => 0,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'pt',
            'name' => 'Portuguese',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'hi',
            'name' => 'Hindi',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'id',
            'name' => 'Indonesian',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'ja',
            'name' => 'Japanese',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'ms',
            'name' => 'Malay',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'ru',
            'name' => 'Russian',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'tr',
            'name' => 'Turkish',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'de',
            'name' => 'German',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'it',
            'name' => 'Italian',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'ko',
            'name' => 'Korean',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'th',
            'name' => 'Thai',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_languages')->insert([
            'symbol' => 'zh',
            'name' => 'Chinese',
            'status' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
