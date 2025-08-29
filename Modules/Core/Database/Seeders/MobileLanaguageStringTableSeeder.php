<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MobileLanaguageStringTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // seeding data for english
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'app_name',
            'value' => 'Flutter BuySell',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'yes_btn',
            'value' => 'Yes',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'no_btn',
            'value' => 'No',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'title_label',
            'value' => 'Title',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'phone_label',
            'value' => 'Phone',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '1',
            'key' => 'name_label',
            'value' => 'Name',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // seeding data for arabic
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'app_name',
            'value' => 'Flutter BuySell',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'yes_btn',
            'value' => 'نعم',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'no_btn',
            'value' => 'رقم',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'title_label',
            'value' => 'عنوان',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'phone_label',
            'value' => 'هاتف',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '2',
            'key' => 'name_label',
            'value' => 'اسم',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // seeding data for spanish
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'app_name',
            'value' => 'Flutter BuySell',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'yes_btn',
            'value' => 'Sí',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'no_btn',
            'value' => 'No',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'title_label',
            'value' => 'Título',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'phone_label',
            'value' => 'Teléfono',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '3',
            'key' => 'name_label',
            'value' => 'Nombre',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        // seeding data for french
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'app_name',
            'value' => 'Flutter BuySell',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'yes_btn',
            'value' => 'Oui',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'no_btn',
            'value' => 'Non',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'title_label',
            'value' => 'Titre',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'phone_label',
            'value' => 'Téléphoner',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_mobile_language_strings')->insert([
            'mobile_language_id' => '4',
            'key' => 'name_label',
            'value' => 'Nom',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
