<?php

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
        Schema::table('psx_frontend_settings', function (Blueprint $table) {
            $table->after("ad_slot", function ($table){
                $table->tinyInteger("is_ads_on")->default(0);
                $table->longText('firebase_config')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_frontend_settings', function (Blueprint $table) {

        });
    }
};
