<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\MobileSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_mobile_settings', function (Blueprint $table) {});
        $mobileSetting = MobileSetting::first();
        if ($mobileSetting) {
            $mobileSetting->version_no = "1.1.3";
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
        Schema::table('psx_mobile_settings', function (Blueprint $table) {});
    }
};
