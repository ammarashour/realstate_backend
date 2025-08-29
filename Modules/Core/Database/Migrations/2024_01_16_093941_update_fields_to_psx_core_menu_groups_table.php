<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\BackendSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('psx_core_menu_groups')->where('group_lang_key', '=', 'vendor_group')->update(['is_show_on_menu' => 0]);
        $backendSetting = BackendSetting::first();
        if ($backendSetting) {
            $backendSetting->vendor_setting = 0;
            $backendSetting->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_core_menu_groups', function (Blueprint $table) {});
    }
};
