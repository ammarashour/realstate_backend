<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_categories')->insert([
            'name' => 'Computers',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Services',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Sports',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Fashions',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Music',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Properties',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_categories')->insert([
            'name' => 'Phones',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
