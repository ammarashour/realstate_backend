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
        $backendSettings = BackendSetting::first();
        if ($backendSettings) {
            $backendSettings->date_format = 'MMMM D, YYYY';
            $backendSettings->backend_version_no = "1.3.1";
            $backendSettings->update();
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
