<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TownshipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Yangon
        DB::table('psx_location_townships')->insert([
            'location_city_id' => '1',
            'name' => 'Kamaryut',
            'lat' => '16.822083',
            'lng' => '96.132957',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_location_townships')->insert([
            'location_city_id' => '1',
            'name' => 'San Chaung',
            'lat' => '16.805096',
            'lng' => '96.132095',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Mandalay
        DB::table('psx_location_townships')->insert([
            'location_city_id' => '2',
            'name' => 'Chan Mya Tharsi',
            'lat' => '21.928873',
            'lng' => '96.090279',
            'ordering' => '3',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_location_townships')->insert([
            'location_city_id' => '2',
            'name' => 'Mahar Aung Myay',
            'lat' => '21.949255',
            'lng' => '96.084801',
            'ordering' => '3',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // Singapore
        DB::table('psx_location_townships')->insert([
            'location_city_id' => '3',
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
