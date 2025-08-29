<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Configuration\BackendSetting;
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
        Schema::table('ps_backend_settings', function (Blueprint $table) {});

        $backendSetting = BackendSetting::first();
        if ($backendSetting) {
            $backendSetting->fe_setting = 1;
            $backendSetting->backend_version_no = "1.1.3";
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
        Schema::table('ps_backend_settings', function (Blueprint $table) {});
    }
};
