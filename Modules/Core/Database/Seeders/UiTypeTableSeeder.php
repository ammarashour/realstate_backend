<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UiTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_ui_types')->insert([
            'name' => 'Dropdown',
            'core_keys_id' => 'uit00001',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Text',
            'core_keys_id' => 'uit00002',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Radio',
            'core_keys_id' => 'uit00003',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Checkbox',
            'core_keys_id' => 'uit00004',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Datetime',
            'core_keys_id' => 'uit00005',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Textarea',
            'core_keys_id' => 'uit00006',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Number',
            'core_keys_id' => 'uit00007',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'MultiSelect',
            'core_keys_id' => 'uit00008',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Image',
            'core_keys_id' => 'uit00009',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Time Only',
            'core_keys_id' => 'uit00010',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_ui_types')->insert([
            'name' => 'Date Only',
            'core_keys_id' => 'uit00011',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

    }
}
