<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\MobileSetting;
use Modules\Core\Entities\ApiCallSetting;

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
            $mobileSetting->version_no = "1.2.1";
            $mobileSetting->update();
        }

        $apiCallSetting = apiCallSetting::first();
        if ($apiCallSetting) {
            $apiCallSetting->delay = 3;
            $apiCallSetting->update();
        } else {
            $newApiCallSetting = new apiCallSetting();
            $newApiCallSetting->delay = 3;
            $newApiCallSetting->save();
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
