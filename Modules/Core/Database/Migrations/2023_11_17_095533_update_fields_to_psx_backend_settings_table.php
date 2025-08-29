<?php

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
        $backendSettings = BackendSetting::first();
        if (isset($backendSettings) || !empty($backendSettings)) {
            //thumbnail 1x
            $backendSettings->landscape_thumb_width = '240';
            $backendSettings->potrait_thumb_height = '480';
            $backendSettings->square_thumb_height = '240';
            //thumbnail 2x
            $backendSettings->landscape_thumb2x_width = '400';
            $backendSettings->potrait_thumb2x_height = '800';
            $backendSettings->square_thumb2x_height = '400';
            //thumbnail x3
            $backendSettings->landscape_thumb3x_width = '720';
            $backendSettings->potrait_thumb3x_height = '800';
            $backendSettings->square_thumb3x_height = '720';
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
