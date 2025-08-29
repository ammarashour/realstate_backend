<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LanguageStringsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // for english
        DB::table('psx_language_strings')->insert([
            'language_id' => '1',
            'key' => 'site_name',
            'value' => 'Flutter Buysell Admin',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '1',
            'key' => 'btn_yes',
            'value' => 'Yes',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '1',
            'key' => 'btn_no',
            'value' => 'No',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '1',
            'key' => 'name',
            'value' => 'Name',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // for arabic
        DB::table('psx_language_strings')->insert([
            'language_id' => '2',
            'key' => 'site_name',
            'value' => 'Flutter Buysell Admin',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '2',
            'key' => 'btn_yes',
            'value' => 'نعم',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '2',
            'key' => 'btn_no',
            'value' => 'لا',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '2',
            'key' => 'btn_no',
            'value' => 'اسم',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // for french
        DB::table('psx_language_strings')->insert([
            'language_id' => '3',
            'key' => 'site_name',
            'value' => 'Flutter Buysell Admin',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '3',
            'key' => 'btn_yes',
            'value' => 'Oui',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '3',
            'key' => 'btn_no',
            'value' => 'Non',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '3',
            'key' => 'name',
            'value' => 'Nom',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // for spanish
        DB::table('psx_language_strings')->insert([
            'language_id' => '4',
            'key' => 'site_name',
            'value' => 'Flutter Buysell Admin',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '4',
            'key' => 'btn_yes',
            'value' => 'si',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '4',
            'key' => 'btn_no',
            'value' => 'No',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_language_strings')->insert([
            'language_id' => '4',
            'key' => 'name',
            'value' => 'Nombre',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
