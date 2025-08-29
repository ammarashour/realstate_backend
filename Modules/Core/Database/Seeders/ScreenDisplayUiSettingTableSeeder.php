<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ScreenDisplayUiSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00001',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00002',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00003',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00004',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00005',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00006',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00007',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00008',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'itm',
            'key' => 'itm00009',
            'is_show' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);


        for ($x = 1; $x <= 11; $x++) {
            DB::table('psx_screen_display_ui_settings')->insert([
                'module_name' => 'itm',
                'key' => $x,
                'is_show' => ($x==6 || $x==7 || $x==9 || $x==10 || $x==11) ? 0 : 1,
                'added_user_id' => '1',
                'added_date' => Carbon::now(),
            ]);
        }

        //------------------------
        DB::table('psx_screen_display_ui_settings')->insert([
            'module_name' => 'usr',
            'key' => 'usr00001',
            'is_show' => 0,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        for ($x = 12; $x <= 23; $x++) {
            DB::table('psx_screen_display_ui_settings')->insert([
                'module_name' => 'usr',
                'key' => $x,
                'is_show' => ($x>=16 && $x<=20)?0:1,
                'added_user_id' => '1',
                'added_date' => Carbon::now(),
            ]);
        }
    }
}
