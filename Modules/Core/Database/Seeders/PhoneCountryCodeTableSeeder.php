<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PhoneCountryCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Myanmar',
            'country_code' => '+95',
            'status' => 1,
            'is_default' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Åland',
            'country_code' => '+358',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Afghanistan',
            'country_code' => '+93',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Albania',
            'country_code' => '+355',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Algeria',
            'country_code' => '+213',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'American Samoa',
            'country_code' => '+1 684',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Andorra',
            'country_code' => '+376',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Angola',
            'country_code' => '+244',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Anguilla',
            'country_code' => '+1 264',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Antigua and Barbuda',
            'country_code' => '+268',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Argentina',
            'country_code' => '+54',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Armenia',
            'country_code' => '+374',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Aruba',
            'country_code' => '+297',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ascension',
            'country_code' => '+247',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Austria',
            'country_code' => '+43',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Azerbaijan',
            'country_code' => '+994',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bahamas',
            'country_code' => '+1 241',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bahrain',
            'country_code' => '+937',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bangladesh',
            'country_code' => '+880',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Barbados',
            'country_code' => '+1 246',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Belarus',
            'country_code' => '+375',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Belize',
            'country_code' => '+501',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Benin',
            'country_code' => '+229',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bermuda',
            'country_code' => '+1 441',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bolivia',
            'country_code' => '+591',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bonaire',
            'country_code' => '+599 7',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bosnia and Herzegovina',
            'country_code' => '+387',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Botswana',
            'country_code' => '+267',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Brazil',
            'country_code' => '+55',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'British Indian Ocean Territory',
            'country_code' => '+246',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'British Virgin Islands',
            'country_code' => '+284',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Brunei Darussalam',
            'country_code' => '+673',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Bulgaria',
            'country_code' => '+359',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Burkina Faso',
            'country_code' => '+226',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Burundi',
            'country_code' => '+257',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cape Verde',
            'country_code' => '+238',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cambodia',
            'country_code' => '+855',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cameroon',
            'country_code' => '+237',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Canada',
            'country_code' => '+1',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Caribbean Netherlands',
            'country_code' => '+599 3',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cameroon',
            'country_code' => '+237',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cayman Islands',
            'country_code' => '+1 345',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Central African Republic',
            'country_code' => '+236',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Chad',
            'country_code' => '+235',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Chile',
            'country_code' => '+56',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'China',
            'country_code' => '+86',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Colombia',
            'country_code' => '+57',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Comoros',
            'country_code' => '+269',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Congo',
            'country_code' => '+242',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cook Islands',
            'country_code' => '+682',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Costa Rica',
            'country_code' => '+506',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Croatia',
            'country_code' => '+385',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cuba',
            'country_code' => '+53',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Curaçao',
            'country_code' => '+599 9',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Cyprus',
            'country_code' => '+357',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Czech Republic',
            'country_code' => '+420',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Denmark',
            'country_code' => '+45',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Djibouti',
            'country_code' => '+253',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Dominica',
            'country_code' => '+1 767',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Dominican Republic',
            'country_code' => '+1 809',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Easter Island',
            'country_code' => '+56',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ecuador',
            'country_code' => '+593',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Egypt',
            'country_code' => '+20',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'El Salvador',
            'country_code' => '+503',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Equatorial Guinea',
            'country_code' => '+240',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Eritrea',
            'country_code' => '+291',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Estonia',
            'country_code' => '+372',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Eswatini',
            'country_code' => '+268',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ethiopia',
            'country_code' => '+251',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Falkland Islands',
            'country_code' => '+500',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Faroe Islands',
            'country_code' => '+298',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Fiji',
            'country_code' => '+679',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Finland',
            'country_code' => '+358',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'France',
            'country_code' => '+33',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'French Guiana',
            'country_code' => '+594',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'French Polynesia',
            'country_code' => '+689',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Gabon',
            'country_code' => '+241',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Gambia',
            'country_code' => '+220',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Georgia',
            'country_code' => '+995',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Germany',
            'country_code' => '+49',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ghana',
            'country_code' => '+233',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Gibraltar',
            'country_code' => '+350',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Greece',
            'country_code' => '+30',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Greenland',
            'country_code' => '+299',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Gabon',
            'country_code' => '+241',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Grenada',
            'country_code' => '+1 743',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guadeloupe',
            'country_code' => '+590',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guam',
            'country_code' => '+1 671',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guatemala',
            'country_code' => '+502',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guinea',
            'country_code' => '+224',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guinea-Bissau',
            'country_code' => '+245',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Guyana',
            'country_code' => '+592',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Haiti',
            'country_code' => '+509',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Honduras',
            'country_code' => '+504',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Hong Kong',
            'country_code' => '+852',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Hungary',
            'country_code' => '+36',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Iceland',
            'country_code' => '+354',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'India',
            'country_code' => '+91',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Indonesia',
            'country_code' => '+62',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Iran',
            'country_code' => '+98',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Iraq',
            'country_code' => '+964',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ireland',
            'country_code' => '+353',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Israel',
            'country_code' => '+972',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Italy',
            'country_code' => '+39',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Jamaica',
            'country_code' => '+1 658',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Jan Mayen',
            'country_code' => '+47 79',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Japan',
            'country_code' => '+81',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Jordan',
            'country_code' => '+962',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kenya',
            'country_code' => '+254',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kiribati',
            'country_code' => '+686',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'South Korea',
            'country_code' => '+82',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kosovo',
            'country_code' => '+383',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kuwait',
            'country_code' => '+965',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kyrgyzstan',
            'country_code' => '+996',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Laos',
            'country_code' => '+856',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Latvia',
            'country_code' => '+371',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Lebanon',
            'country_code' => '+961',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Lesotho',
            'country_code' => '+266',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Liberia',
            'country_code' => '+231',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Libya',
            'country_code' => '+218',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Liechtenstein',
            'country_code' => '+423',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Lithuania',
            'country_code' => '+370',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Luxembourg',
            'country_code' => '+352',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Madagascar',
            'country_code' => '+261',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Malawi',
            'country_code' => '+265',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Malaysia',
            'country_code' => '+60',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Maldives',
            'country_code' => '+960',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mali',
            'country_code' => '+223',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Malta',
            'country_code' => '+356',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Marshall Islands',
            'country_code' => '+692',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Martinique',
            'country_code' => '+596',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mauritania',
            'country_code' => '+222',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mauritius',
            'country_code' => '+230',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mexico',
            'country_code' => '+52',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Moldova',
            'country_code' => '+373',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Monaco',
            'country_code' => '+377',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mongolia',
            'country_code' => '+976',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Montenegro',
            'country_code' => '+382',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Montserrat',
            'country_code' => '+1 664',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Morocco',
            'country_code' => '+212',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kiribati',
            'country_code' => '+686',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Mozambique',
            'country_code' => '+258',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Namibia',
            'country_code' => '+264',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Nauru',
            'country_code' => '+674',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Nepal',
            'country_code' => '+977',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kiribati',
            'country_code' => '+686',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Netherlands',
            'country_code' => '+31',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Nevis',
            'country_code' => '+1 869',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Kiribati',
            'country_code' => '+686',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'New Caledonia',
            'country_code' => '+687',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'New Zealand',
            'country_code' => '+64',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Nicaragua',
            'country_code' => '+505',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Niger',
            'country_code' => '+227',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Nigeria',
            'country_code' => '+234',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Niue',
            'country_code' => '+683',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Norfolk Island',
            'country_code' => '+672 3',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'North Macedonia',
            'country_code' => '+389',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Northern Cyprus',
            'country_code' => '+90 392',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Northern Ireland',
            'country_code' => '+44 28',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Northern Mariana Islands',
            'country_code' => '+1 670',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Norway',
            'country_code' => '+47',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Oman',
            'country_code' => '+968',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Pakistan',
            'country_code' => '+92',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Palau',
            'country_code' => '+680',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Palestine, State of',
            'country_code' => '+970',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Panama',
            'country_code' => '+507',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Papua New Guinea',
            'country_code' => '+675',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Paraguay',
            'country_code' => '+595',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Peru',
            'country_code' => '+51',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Philippines',
            'country_code' => '+63',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Pitcairn Islands',
            'country_code' => '+64',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Palau',
            'country_code' => '+680',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Poland',
            'country_code' => '+48',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Portugal',
            'country_code' => '+351',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Puerto Rico',
            'country_code' => '+1 787',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Qatar',
            'country_code' => '+974',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Réunion',
            'country_code' => '+262',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Romania',
            'country_code' => '+40',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Russia',
            'country_code' => '+7',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Rwanda',
            'country_code' => '+250',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saba',
            'country_code' => '+599 4',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Barthélemy',
            'country_code' => '+590',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Helena',
            'country_code' => '+290',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Kitts and Nevis',
            'country_code' => '+1 869',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Lucia',
            'country_code' => '+1 758',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Martin (France)',
            'country_code' => '+590',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Pierre and Miquelon',
            'country_code' => '+508',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saint Vincent and the Grenadines',
            'country_code' => '+1 784',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Samoa',
            'country_code' => '+685',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'San Marino',
            'country_code' => '+378',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'São Tomé and Príncipe',
            'country_code' => '+239',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Saudi Arabia',
            'country_code' => '+966',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Senegal',
            'country_code' => '+221',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Serbia',
            'country_code' => '+381',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Seychelles',
            'country_code' => '+248',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Sierra Leone',
            'country_code' => '+232',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Singapore',
            'country_code' => '+65',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Sint Eustatius',
            'country_code' => '+599 3',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Palau',
            'country_code' => '+680',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Slovakia',
            'country_code' => '+421',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Slovenia',
            'country_code' => '+386',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Solomon Islands',
            'country_code' => '+677',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Somalia',
            'country_code' => '+252',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'South Africa',
            'country_code' => '+27',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'South Ossetia',
            'country_code' => '+995 34',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'South Sudan',
            'country_code' => '+211',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Spain',
            'country_code' => '+34',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Sri Lanka',
            'country_code' => '+94',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Sudan',
            'country_code' => '+249',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Suriname',
            'country_code' => '+597',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Svalbard',
            'country_code' => '+47 79',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Sweden',
            'country_code' => '+46',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Switzerland',
            'country_code' => '+41',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Syria',
            'country_code' => '+963',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Taiwan',
            'country_code' => '+886',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tajikistan',
            'country_code' => '+992',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tanzania',
            'country_code' => '+255',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Thailand',
            'country_code' => '+66',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'East Timor (Timor-Leste)',
            'country_code' => '+670',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Togo',
            'country_code' => '+228',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tokelau',
            'country_code' => '+690',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tonga',
            'country_code' => '+676',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Transnistria',
            'country_code' => '+373 2',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Trinidad and Tobago',
            'country_code' => '+1 868',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tristan da Cunha',
            'country_code' => '+290 8',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tunisia',
            'country_code' => '+216',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Turkey',
            'country_code' => '+90',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Turkmenistan',
            'country_code' => '+993',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Turks and Caicos Islands',
            'country_code' => '+1 649',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Tuvalu',
            'country_code' => '+688',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Uganda',
            'country_code' => '+256',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Ukraine',
            'country_code' => '+380',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'United Arab Emirates',
            'country_code' => '+971',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Somalia',
            'country_code' => '+252',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'United Kingdom',
            'country_code' => '+44',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'United States',
            'country_code' => '+1',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Uruguay',
            'country_code' => '+598',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'US Virgin Islands',
            'country_code' => '+1 340',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Uzbekistan',
            'country_code' => '+998',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Vanuatu',
            'country_code' => '+678',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Venezuela',
            'country_code' => '+58',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Vietnam',
            'country_code' => '+84',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Wallis and Futuna',
            'country_code' => '+681',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Yemen',
            'country_code' => '+967',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Zambia',
            'country_code' => '+260',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Zanzibar',
            'country_code' => '+255 24',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_phone_country_codes')->insert([
            'country_name' => 'Zimbabwe',
            'country_code' => '+263',
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
