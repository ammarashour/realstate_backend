<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('psx_vendor_languages_strings', function (Blueprint $table) {
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

            // create vendor language and vendor language string modules
            $vendor_lang_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_language_module')->first();
            $vendor_lang_str_module = DB::table('psx_modules')->where('lang_key', '=', 'vendor_language_string_module')->first();

            if (empty($vendor_lang_module)) {
                $vendor_lang_module_id = DB::table('psx_modules')->insertGetId([
                    'id' => 75,
                    'title' => 'vendor_language',
                    'lang_key' => 'vendor_language_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "0",
                    'status' => '1',
                    'route_name' => 'vendor_language.index'
                ]);
            }
            if (empty($vendor_lang_str_module)) {
                DB::table('psx_modules')->insert([
                    'id' => 76,
                    'title' => 'vendor_language_string',
                    'lang_key' => 'vendor_language_string_module',
                    'added_date' => Carbon::now(),
                    'added_user_id' => '1',
                    'updated_date' => Carbon::now(),
                    'is_not_from_sidebar' => "1",
                    'status' => '1',
                    'route_name' => 'vendor_language_string.index'
                ]);
            }

            // create vendor language menus
            $vendor_lang_menus = CoreMenu::where('module_name', '=', 'vendor_language')->first();
            if (empty($vendor_lang_menus)) {
                $menu = new CoreMenu();
                $menu->module_name = 'vendor_language';
                $menu->module_desc = 'Vendor Language';
                $menu->module_lang_key = 'vendor_language_module';
                $menu->ordering = 6;
                $menu->is_show_on_menu = 1;
                $menu->module_id = $vendor_lang_module_id;
                $menu->core_sub_menu_group_id = 14;
                $menu->added_user_id = 1;
                $menu->save();

                DB::table('psx_modules')->where('id', '=', $vendor_lang_module_id)->update(['menu_id' => $menu->id]);
            }
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 75,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => 76,
                'permission_id' => '1,2,3,4',
                'added_date' => Carbon::now(),
                'added_user_id' => 1,
                'updated_date' => Carbon::now()
            ]);
        } catch (Exception $_) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_vendor_languages_strings');
    }
};
