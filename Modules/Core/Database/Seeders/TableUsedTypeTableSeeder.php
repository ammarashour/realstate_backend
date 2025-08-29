<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TableUsedTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = [
            [
                "name" => "Basic",
                "added_user_id" => 1 //admin
            ],
            [
                "name" => "Medium",
                "added_user_id" => 1 //admin
            ],
            [
                "name" => "Advanced",
                "added_user_id" => 1 //admin
            ],
        ];

        foreach ($tables as $table){
            DB::table('psx_table_used_types')->insert([
                "name" => $table['name'],
                "added_user_id" => $table['added_user_id']
            ]);
        }
    }
}
