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
        Schema::table('psx_backend_settings', function (Blueprint $table) {

        });
        Schema::table('psx_backend_settings', function (Blueprint $table) {
            $table->after("map_key", function ($table){
                $table->tinyInteger('is_watermask')->default(1);
                $table->Integer('watermask_image_size')->default(20);
                $table->Integer('font_size')->default(5);
                $table->String('position')->default('center');
                $table->Integer('padding')->default(0);
                $table->String('watermask_color')->default("");
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
        Schema::table('psx_backend_settings', function (Blueprint $table) {
            $table->dropColumn(['is_watermask']);
            $table->dropColumn(['watermask_image_size']);
            $table->dropColumn(['font_size']);
            $table->dropColumn(['position']);
            $table->dropColumn(['padding']);
            $table->dropColumn(['watermask_color']);
        });


    }
};
