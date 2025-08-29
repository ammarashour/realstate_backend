<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Entities\Menu\Module;

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
            $module = new Module();
            $module->id = 88;
            $module->title = 'download_db';
            $module->lang_key = 'download_db_module';
            $module->added_user_id = 1;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'download_db.index';
            $module->save();

            $coreMenu = new CoreMenu();
            $coreMenu->module_name = 'download_db';
            $coreMenu->module_desc = 'Download DB';
            $coreMenu->module_lang_key = 'download_db_module';
            $coreMenu->ordering = 2;
            $coreMenu->is_show_on_menu = 1;
            $coreMenu->module_id = $module->id;
            $coreMenu->core_sub_menu_group_id = 14;
            $coreMenu->added_user_id = 1;
            $coreMenu->save();

            DB::table('psx_modules')->where('id', '=', $module->id)->update(['menu_id' => $coreMenu->id]);

            DB::table('psx_role_permissions')->insert([
                'role_id' => 1,
                'module_id' => $module->id,
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
        Schema::table('', function (Blueprint $table) {});
    }
};
