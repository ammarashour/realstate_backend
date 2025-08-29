<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AvailableCurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'USD',
            'name' => 'USD Dollar',
            'status' => 1,
            'is_default' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'S$',
            'currency_short_form' => 'SGD',
            'name' => 'Singapore Dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '€',
            'currency_short_form' => 'EUR',
            'name' => 'Euro',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '¥',
            'currency_short_form' => 'JPY',
            'name' => 'Japanese Yen',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'MMK',
            'currency_short_form' => 'MMK',
            'name' => 'Myanmar Kyat',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₽',
            'currency_short_form' => 'RUB',
            'name' => 'Russian rouble',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Af',
            'currency_short_form' => 'AFN',
            'name' => '	Afghan afghani',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Lek',
            'currency_short_form' => 'ALL',
            'name' => 'Albanian lek',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '€',
            'currency_short_form' => 'EUR',
            'name' => 'Euro',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Kz',
            'currency_short_form' => 'AOA',
            'name' => 'Angolan kwanza',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'XCD',
            'name' => 'Eastern Caribbean dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'ARS',
            'name' => 'Argentine peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '֏',
            'currency_short_form' => 'AMD',
            'name' => '	Armenian dram',
            'status' => 1,
            'is_default' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'ƒ',
            'currency_short_form' => 'AWG',
            'name' => '	Aruban florin',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '£',
            'currency_short_form' => 'SHP',
            'name' => 'Saint Helena pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'AUD',
            'name' => 'Australian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₼',
            'currency_short_form' => 'AZN',
            'name' => 'Azerbaijani manat',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'BSD',
            'name' => 'Bahamian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'BBD',
            'name' => 'Barbadian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Rbl',
            'currency_short_form' => 'BYN',
            'name' => 'Belarusian rubel',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '	$',
            'currency_short_form' => 'BZD',
            'name' => 'Belize dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XOF',
            'name' => 'West African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'BMD',
            'name' => 'Bermudian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Nu',
            'currency_short_form' => 'BTN',
            'name' => 'Bhutanese ngultrum',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₹',
            'currency_short_form' => 'INR',
            'name' => 'Indian rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Bs',
            'currency_short_form' => 'BOB',
            'name' => 'Bolivian boliviano',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'P',
            'currency_short_form' => 'BWP',
            'name' => 'Botswana pula',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'R$',
            'currency_short_form' => 'BRL',
            'name' => 'Brazilian real',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'BND',
            'name' => 'Brunei dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Lev',
            'currency_short_form' => 'BGN',
            'name' => 'Bulgarian lev',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XOF',
            'name' => 'West African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'BIF',
            'name' => 'Burundian franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'CR',
            'currency_short_form' => 'KHR',
            'name' => 'Cambodian riel',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XAF',
            'name' => 'Central African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'CVE',
            'name' => 'Cape Verdean escudo',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'KYD',
            'name' => '	Cayman Islands dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XAF',
            'name' => 'Central African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'CLP',
            'name' => 'Chilean peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '¥',
            'currency_short_form' => 'CNY',
            'name' => 'Renminbi',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'COP',
            'name' => 'Colombian peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'KMF',
            'name' => 'Comorian franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'CDF',
            'name' => 'Congolese franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XAF',
            'name' => 'Central African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'NZD',
            'name' => 'New Zealand dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₡',
            'currency_short_form' => 'CRC',
            'name' => 'Costa Rican colón',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XOF',
            'name' => 'West African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kn',
            'currency_short_form' => 'HRK',
            'name' => 'Croatian kuna',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'CUP',
            'name' => 'Cuban peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'ƒ',
            'currency_short_form' => 'ANG',
            'name' => 'Netherlands Antillean guilder',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Kč',
            'currency_short_form' => 'CZK',
            'name' => 'Czech koruna',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kr',
            'currency_short_form' => 'DKK',
            'name' => 'Danish krone',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '	Fr',
            'currency_short_form' => 'DJF',
            'name' => 'Djiboutian franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'XCD',
            'name' => 'Eastern Caribbean dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'LE',
            'currency_short_form' => 'EGP',
            'name' => 'Egyptian pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Nkf',
            'currency_short_form' => 'ERN',
            'name' => 'Eritrean nakfa',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'L',
            'currency_short_form' => 'SZL',
            'name' => 'Swazi lilangeni',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'R',
            'currency_short_form' => 'ZAR',
            'name' => 'South African rand',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Br',
            'currency_short_form' => 'ETB',
            'name' => 'Ethiopian birr',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '£',
            'currency_short_form' => 'FKP',
            'name' => 'Falkland Islands pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kr',
            'currency_short_form' => 'DKK',
            'name' => 'Danish krone',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'FJD',
            'name' => 'Fijian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XPF',
            'name' => 'CFP franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XAF',
            'name' => 'Central African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'D',
            'currency_short_form' => 'GMD',
            'name' => 'Gambian dalasi',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₾',
            'currency_short_form' => 'GEL',
            'name' => 'Georgian lari',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₵',
            'currency_short_form' => 'GHS',
            'name' => 'Ghanaian cedi',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '£',
            'currency_short_form' => 'GIP',
            'name' => 'Gibraltar pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '€',
            'currency_short_form' => 'EUR',
            'name' => 'Danish krone',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'XCD',
            'name' => 'Eastern Caribbean dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Q',
            'currency_short_form' => 'GTQ',
            'name' => 'Guatemalan quetzal',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '£',
            'currency_short_form' => 'GBP',
            'name' => 'Sterling',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'GNF',
            'name' => 'Guinean franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XOF',
            'name' => 'West African CFA franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'GYD',
            'name' => 'Guyanese dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'G',
            'currency_short_form' => 'HTG',
            'name' => 'Haitian gourde',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'L',
            'currency_short_form' => 'HNL',
            'name' => 'Honduran lempira',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'HKD',
            'name' => 'Hong Kong dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Ft',
            'currency_short_form' => 'HUF',
            'name' => 'Hungarian forint',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kr',
            'currency_short_form' => 'ISK',
            'name' => 'Icelandic króna',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Rp',
            'currency_short_form' => 'IDR',
            'name' => 'Indonesian rupiah',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Rl',
            'currency_short_form' => 'IRR',
            'name' => 'Iranian rial',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'ID',
            'currency_short_form' => 'IQD',
            'name' => 'Iraqi dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₪',
            'currency_short_form' => 'ILS',
            'name' => 'Israeli new shekel',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'JMD',
            'name' => 'Jamaican dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'JD',
            'currency_short_form' => 'JOD',
            'name' => 'Jordanian dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₸',
            'currency_short_form' => 'KZT',
            'name' => 'Kazakhstani tenge',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Sh',
            'currency_short_form' => 'KES',
            'name' => 'Kenyan shilling',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₩',
            'currency_short_form' => 'KRW',
            'name' => 'South Korean won',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'KD',
            'currency_short_form' => 'KWD',
            'name' => 'Kuwaiti dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'som',
            'currency_short_form' => 'KGS',
            'name' => 'Kyrgyz som',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₭',
            'currency_short_form' => 'LAK',
            'name' => 'Lao kip',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'LL',
            'currency_short_form' => 'LBP',
            'name' => 'Lebanese pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'L',
            'currency_short_form' => 'LSL',
            'name' => 'Lesotho loti',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'R',
            'currency_short_form' => 'ZAR',
            'name' => 'South African rand',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'LRD',
            'name' => 'Liberian dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'LD',
            'currency_short_form' => 'LYD',
            'name' => 'Libyan dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'CHF',
            'name' => 'Swiss franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'MOP$',
            'currency_short_form' => 'MOP',
            'name' => 'Macanese pataca',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Ar',
            'currency_short_form' => 'MGA',
            'name' => 'Malagasy ariary',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'K',
            'currency_short_form' => 'MWK',
            'name' => 'Malawian kwacha',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'RM',
            'currency_short_form' => 'MYR',
            'name' => 'Malaysian ringgit',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Rf',
            'currency_short_form' => 'MVR',
            'name' => 'Maldivian rufiyaa',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'UM',
            'currency_short_form' => 'MRU',
            'name' => 'Mauritanian ouguiya',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Re',
            'currency_short_form' => 'MUR',
            'name' => 'Mauritian rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'MXN',
            'name' => 'Mexican peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Leu',
            'currency_short_form' => 'MDL',
            'name' => 'Moldovan leu',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₮',
            'currency_short_form' => 'MNT',
            'name' => 'Mongolian tögrög',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DH',
            'currency_short_form' => 'MAD',
            'name' => 'Moroccan dirham',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Mt',
            'currency_short_form' => 'MZN',
            'name' => 'Mozambican metical',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Re',
            'currency_short_form' => 'NPR',
            'name' => 'Nepalese rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'XPF',
            'name' => 'CFP franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'C$',
            'currency_short_form' => 'NIO',
            'name' => 'Nicaraguan córdoba',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₦',
            'currency_short_form' => 'NGN',
            'name' => 'Nigerian naira',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DEN',
            'currency_short_form' => 'MKD',
            'name' => 'Macedonian denar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₺',
            'currency_short_form' => 'TRY',
            'name' => 'Turkish lira',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kr',
            'currency_short_form' => 'NOK',
            'name' => 'Norwegian krone',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'RO',
            'currency_short_form' => 'OMR',
            'name' => 'Omani rial',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Re',
            'currency_short_form' => 'PKR',
            'name' => 'Pakistani rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'B/',
            'currency_short_form' => 'PAB',
            'name' => 'Panamanian balboa',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'K',
            'currency_short_form' => 'PGK',
            'name' => 'Papua New Guinean kina',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₲',
            'currency_short_form' => 'PYG',
            'name' => 'Paraguayan guaraní',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'S/',
            'currency_short_form' => 'PEN',
            'name' => 'Peruvian sol',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₱',
            'currency_short_form' => 'PHP',
            'name' => 'Philippine peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'zł',
            'currency_short_form' => 'PLN',
            'name' => 'Polish złoty',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'QR',
            'currency_short_form' => 'QAR',
            'name' => 'Qatari riyal',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Leu',
            'currency_short_form' => 'RON',
            'name' => 'Romanian leu',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Fr',
            'currency_short_form' => 'RWF',
            'name' => 'Rwandan franc',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DA',
            'currency_short_form' => 'DZD',
            'name' => 'Algerian dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'UM',
            'currency_short_form' => 'MRU',
            'name' => 'Mauritanian ouguiya',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DH',
            'currency_short_form' => 'MAD',
            'name' => 'Moroccan dirham',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'WST',
            'name' => 'Samoan tālā',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Db',
            'currency_short_form' => 'STN',
            'name' => 'São Tomé and Príncipe dobra',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Rl',
            'currency_short_form' => 'SAR',
            'name' => 'Saudi riyal',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DIN',
            'currency_short_form' => 'RSD',
            'name' => 'Serbian dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Re',
            'currency_short_form' => 'SCR',
            'name' => 'Seychellois rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Le',
            'currency_short_form' => 'SLE',
            'name' => 'Sierra Leonean leone',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'SBD',
            'name' => 'Solomon Islands dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Sh',
            'currency_short_form' => 'SOS',
            'name' => 'Somali shilling',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Re',
            'currency_short_form' => 'LKR',
            'name' => 'Sri Lankan rupee',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'LS',
            'currency_short_form' => 'SDG',
            'name' => 'Sudanese pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'SRD',
            'name' => 'Surinamese dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'kr',
            'currency_short_form' => 'SEK',
            'name' => 'Swedish krona',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'LS',
            'currency_short_form' => 'SYP',
            'name' => 'Syrian pound',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'SM',
            'currency_short_form' => 'TJS',
            'name' => 'Tajikistani somoni',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Sh',
            'currency_short_form' => 'TZS',
            'name' => 'Tanzanian shilling',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '฿',
            'currency_short_form' => 'THB',
            'name' => 'Thai baht',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'TTD',
            'name' => 'Trinidad and Tobago dollar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'DT',
            'currency_short_form' => 'TND',
            'name' => 'Tunisian dinar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₺',
            'currency_short_form' => 'TRY',
            'name' => 'Turkish lira',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'm',
            'currency_short_form' => 'TMT',
            'name' => 'Turkmenistani manat',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₴',
            'currency_short_form' => 'UAH',
            'name' => 'Ukrainian hryvnia',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '$',
            'currency_short_form' => 'UYU',
            'name' => '	Uruguayan peso',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'soum',
            'currency_short_form' => 'UZS',
            'name' => 'Uzbekistani soum',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'VT',
            'currency_short_form' => 'VUV',
            'name' => 'Vanuatu vatu',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Bs.S',
            'currency_short_form' => 'VES',
            'name' => 'Venezuelan sovereign bolívar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'Bs.D',
            'currency_short_form' => 'VED',
            'name' => 'Venezuelan digital bolívar',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => '₫',
            'currency_short_form' => 'VND',
            'name' => 'Vietnamese đồng',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'YER',
            'currency_short_form' => 'Rl',
            'name' => 'Yemeni rial',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_available_currencies')->insert([
            'currency_symbol' => 'K',
            'currency_short_form' => 'ZMW',
            'name' => 'Zambian kwacha',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);



    }
}
