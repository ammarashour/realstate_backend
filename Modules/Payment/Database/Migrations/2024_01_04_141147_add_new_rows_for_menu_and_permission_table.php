<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Entities\Icon;
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
            $icon = new Icon();
            $icon->id = 19;
            $icon->icon_name = 'cash';
            $icon->added_user_id = 1;
            $icon->save();

            $coreSubMenuGroup = new CoreSubMenuGroup();
            $coreSubMenuGroup->sub_menu_name = 'subscriptions';
            $coreSubMenuGroup->sub_menu_desc = 'subscriptions';
            $coreSubMenuGroup->icon_id = 19;
            $coreSubMenuGroup->sub_menu_lang_key = 'subscription';
            $coreSubMenuGroup->ordering = 2;
            $coreSubMenuGroup->is_show_on_menu = 1;
            $coreSubMenuGroup->core_menu_group_id = 5;
            $coreSubMenuGroup->added_user_id = 1;
            $coreSubMenuGroup->is_dropdown = 1;
            $coreSubMenuGroup->save();

            $module = new Module();
            $module->id = 86;
            $module->title = 'vendor_subscription_plan';
            $module->lang_key = 'vendor_subscription_plan_module';
            $module->added_user_id = 1;
            $module->is_not_from_sidebar = 0;
            $module->status = 1;
            $module->route_name = 'vendor_subscription_plan.index';
            $module->save();

            DB::table('psx_core_sub_menu_groups')->where('id', '=', $coreSubMenuGroup->id)->update(['module_id' => $module->id]);

            $coreMenu = new CoreMenu();
            $coreMenu->module_name = 'vendor_subscription_plan';
            $coreMenu->module_desc = 'Plans';
            $coreMenu->module_lang_key = 'vendor_subscription_plan_module';
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
