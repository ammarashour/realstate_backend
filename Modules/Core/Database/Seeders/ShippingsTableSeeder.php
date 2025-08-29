<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ShippingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_shippings')->insert([
            'shop_id' => '1',
            'price' => 30,
            'name' => 'North Area',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_shippings')->insert([
            'shop_id' => '1',
            'price' => 30,
            'name' => 'South Area',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_shippings')->insert([
            'shop_id' => '1',
            'price' => 30,
            'name' => 'East Area',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_shippings')->insert([
            'shop_id' => '1',
            'price' => 30,
            'name' => 'West Area',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
