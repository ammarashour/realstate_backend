<?php

use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        $settingEnv = Setting::where("setting_env", "vendor_subscription_config")->first();
        $setting = json_decode($settingEnv->setting);
        $setting->notic_days = "7";
        $settingEnv->setting = json_encode($setting);
        $settingEnv->update();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_settings', function (Blueprint $table) {});
    }
};
