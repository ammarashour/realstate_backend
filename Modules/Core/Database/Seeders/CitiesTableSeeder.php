<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_location_cities')->insert([
            'name' => 'Yangon',
            'lat' => '16.865044',
            'lng' => '96.203674',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_location_cities')->insert([
            'name' => 'Mandalay',
            'lat' => '21.959900',
            'lng' => '96.086601',
            'ordering' => '2',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_location_cities')->insert([
            'name' => 'Singapore',
            'lat' => '1.354200',
            'lng' => '103.819839',
            'ordering' => '3',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
