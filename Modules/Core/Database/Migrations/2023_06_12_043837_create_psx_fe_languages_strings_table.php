<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_fe_languages_strings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id');
            $table->string('key');
            $table->string('value');
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();

            $table->timestamps();
        });

        try{

            // create fe language and fe language string modules
            $fe_lang_module = DB::table('psx_modules')->where('lang_key','=','fe_language_module')->first();
            $fe_lang_str_module = DB::table('psx_modules')->where('lang_key','=','fe_language_string_module')->first();

            if(empty($fe_lang_module)){
                $fe_lang_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 72,
                    'title' => 'fe_language',
                    'lang_key' => 'fe_language_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'fe_language.index'
                ]);
            }
            if(empty($fe_lang_str_module)){
                DB::table('psx_modules')->insert([
                    'id' => 73,
                    'title' => 'fe_language_string',
                    'lang_key' => 'fe_language_string_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'fe_language_string.index'
                ]);
            }

            // create fe language menus
            
            $fe_lang_menus = DB::table('psx_core_menus')->where('module_name','=','fe_language')->first();

            if(empty($fe_lang_menus) ){
                $fe_lang_menus_id = DB::table('psx_core_menus')->insertGetId([
                    'id' => 68,
                    'module_name' => 'fe_language',
                    'module_desc' => 'Frontend Language',
                    'module_lang_key' => 'fe_language_module',
                    'ordering' => '6',
                    'is_show_on_menu' => '1',
                    'core_sub_menu_group_id' => '14',
                    'module_id' => $fe_lang_module_id,
                    'added_user_id' => '1',
                    'added_date' => Carbon::now(),
                ]);

                DB::table('psx_modules')->where('id','=',$fe_lang_module_id)->update(['menu_id' => $fe_lang_menus_id]);
            }
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 72,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 73,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);

        }catch(Exception $_) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_fe_languages_strings');
    }
};
