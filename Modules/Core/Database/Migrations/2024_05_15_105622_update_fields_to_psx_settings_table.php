<?php

use Modules\Core\Entities\Configuration\Setting;
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
        $setting = Setting::where('setting_env', 'system_config')->first();
        $selcted_array = json_decode($setting->setting, true);

        $selcted_array['hide_price_setting'] = "0";
        $setting->setting = json_encode($selcted_array);
        $setting->update();
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
