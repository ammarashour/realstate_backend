<?php

use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Configuration\MobileSetting;
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
        $backendSettings = BackendSetting::first();
        if ($backendSettings) {
            $backendSettings->backend_version_no = "1.4.2";
            $backendSettings->update();
        }

        $mobileSetting = MobileSetting::first();
        if ($mobileSetting) {
            $mobileSetting->version_no = "1.4.2";
            $mobileSetting->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_backend_settings', function (Blueprint $table) {});
    }
};
