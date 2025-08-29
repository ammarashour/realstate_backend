<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_shops')->insert([
            'name' => 'All in One',
            'description' => 'desc',
            'email' => 'allinone@gmail.com',
            'lat' => '15.135764',
            'lng' => '95.820778',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_shops')->insert([
            'name' => 'The Best',
            'description' => 'desc',
            'email' => 'thebest@gmail.com',
            'lat' => '16.805281',
            'lng' => '96.156113',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
