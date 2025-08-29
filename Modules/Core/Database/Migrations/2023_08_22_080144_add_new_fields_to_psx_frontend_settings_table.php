<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\FrontendSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!isset(FrontendSetting::first()->color_changed_code)) {
            Schema::table('psx_frontend_settings', function (Blueprint $table) {
                $table->after("price_format", function ($table) {

                    $table->String('color_changed_code')->default('101');
                });
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_frontend_settings', function (Blueprint $table) {});
    }
};
