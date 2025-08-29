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
        $setting = new Setting();
        $setting->setting_env = "vendor_subscription_config";
        $setting->save();

        $selected_subscription_setting = [];
        $reference_setting = [];

        $selected_setting = array(
            [
                "id" => "SUBSCRIPTION_PLANS"
            ]
        );

        $ref_setting = array(
            [
                "id" => "FREE",
                "value" => "Free"
            ],
            [
                "id" => "SUBSCRIPTION_PLANS",
                "value" => "Subscription Plans"
            ]
        );

        $selected_subscription_setting["subscription_plan"] = $selected_setting;
        $reference_setting["subscription_plans"] = $ref_setting;

        $setting->setting = $selected_subscription_setting;
        $setting->ref_selection = $reference_setting;

        $setting->save();
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
