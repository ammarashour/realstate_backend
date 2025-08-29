<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_subcategories')->insert([
            'name' => 'Notebook',
            'category_id' => '1',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Ultrabook',
            'category_id' => '1',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Gaming PC',
            'category_id' => '1',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Drivers',
            'category_id' => '2',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Doctor',
            'category_id' => '2',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Computer Technician',
            'category_id' => '2',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Sport Shirts',
            'category_id' => '3',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Shoes',
            'category_id' => '3',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Skirt',
            'category_id' => '4',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Sweater',
            'category_id' => '4',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Kid Fashion',
            'category_id' => '4',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Myanmar Dress',
            'category_id' => '4',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Western Dress',
            'category_id' => '4',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Ukulele',
            'category_id' => '5',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Guitar',
            'category_id' => '5',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Headphone',
            'category_id' => '5',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Earphone',
            'category_id' => '5',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Apartments',
            'category_id' => '6',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Houses',
            'category_id' => '6',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Samsung',
            'category_id' => '7',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Apple',
            'category_id' => '7',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Oppo',
            'category_id' => '7',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Xiaomi',
            'category_id' => '7',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_subcategories')->insert([
            'name' => 'Keypad',
            'category_id' => '7',
            'ordering' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
