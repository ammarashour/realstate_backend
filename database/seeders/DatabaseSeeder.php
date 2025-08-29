<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Generator as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker)
    {




//        \App\Models\User::factory()->count(20)->create()->each(function ($item) use($faker){
//
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                    'core_keys_id'=>'ps-usr00001',
//                    'value'=>$faker->word,
//                    'ui_type_id'=>'uit00002',
//                    'updated_user_id'=>1,
//            ]);
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                'core_keys_id'=>'ps-usr00002',
//                'value'=>rand(0,1),
//                'ui_type_id'=>'uit00001',
//                'updated_user_id'=>1,
//            ]);
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                'core_keys_id'=>'ps-usr00003',
//                'value'=>$faker->paragraph,
//                'ui_type_id'=>'uit00001',
//                'updated_user_id'=>1,
//            ]);
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                'core_keys_id'=>'ps-usr00004',
//                'value'=>rand(0,20),
//                'ui_type_id'=>'uit00001',
//                'updated_user_id'=>1,
//            ]);
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                'core_keys_id'=>'ps-usr00005',
//                'value'=>rand(0,30),
//                'ui_type_id'=>'uit00006',
//                'updated_user_id'=>1,
//            ]);
//            DB::table('user_infos')->insert([
//                'user_id' => $item->id,
//                'core_keys_id'=>'ps-usr00006',
//                'value'=>rand(0,25),
//                'ui_type_id'=>'uit00006',
//                'updated_user_id'=>1,
//            ]);
//
//        });


    }
}
