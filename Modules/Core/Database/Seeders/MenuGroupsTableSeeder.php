<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MenuGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_core_menu_groups')->insert([
            'group_name' => 'Entry',
            'group_icon' => '',
            'group_lang_key' => 'entry_group',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_menu_groups')->insert([
            'group_name' => 'Other',
            'group_icon' => '',
            'group_lang_key' => 'other_group',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_core_menu_groups')->insert([
            'group_name' => 'Setting',
            'group_icon' => '',
            'group_lang_key' => 'settings_group',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

    }
}
