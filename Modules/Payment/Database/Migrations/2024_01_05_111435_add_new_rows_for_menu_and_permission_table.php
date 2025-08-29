<?php

use Carbon\Carbon;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Illuminate\Database\Migrations\Migration;

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
            $coreSubMenuGroup = CoreSubMenuGroup::where('sub_menu_lang_key', '=', 'subscription')->first();

            $module = new Module();
            $module->id = 87;
            $module->title = 'vendor_subscription_report';
            $module->lang_key = 'vendor_subscription_report_module';
            $module->added_user_id = 1;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'vendor_subscription_report.index';
            $module->save();

            DB::table('psx_core_sub_menu_groups')->where('id', '=', $coreSubMenuGroup->id)->update(['module_id' => $module->id]);

            $coreMenu = new CoreMenu();
            $coreMenu->module_name = 'vendor_subscription_report';
            $coreMenu->module_desc = 'Vendor subscription report';
            $coreMenu->module_lang_key = 'vendor_subscription_report_module';
            $coreMenu->ordering = 2;
            $coreMenu->is_show_on_menu = 1;
            $coreMenu->module_id = $module->id;
            $coreMenu->core_sub_menu_group_id = $coreSubMenuGroup->id;
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
