<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $iconId = DB::table('psx_icons')->insertGetId([
            'icon_name' => 'ballPenPoint',
            'added_date' => now(),
            'added_user_id' => 1,
        ]);

        $moduleId = DB::table('psx_modules')->insertGetId([
            'title' => 'customize_theme',
            'lang_key' => 'customize_theme_module',
            'added_date' => now(),
            'added_user_id' => 1,
            'is_not_from_sidebar' => 0,
            'status' => 1,
            'route_name' => 'customize_theme.index'
        ]);

        $subMenuId = DB::table('psx_core_sub_menu_groups')->insertGetId([
            'sub_menu_name' => 'customize_theme',
            'sub_menu_desc' => 'Customize Theme Setting',
            'icon_id' => $iconId,
            'sub_menu_lang_key' => 'customize_theme_group',
            'ordering' => 17,
            'is_show_on_menu' => 1,
            'module_id' => $moduleId,
            'core_menu_group_id' => 3,
            'added_date' => now(),
            'added_user_id' => 1,
            'is_dropdown' => 0
        ]);

        DB::table('psx_modules')->where('id', $moduleId)->update([
            'sub_menu_id' => $subMenuId
        ]);

        DB::table('psx_role_permissions')->insert([
            'role_id' => 1,
            'module_id' => $moduleId,
            'permission_id' => '1,2,3,4',
            'added_date' => now(),
            'added_user_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
