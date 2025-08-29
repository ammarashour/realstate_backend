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
            $table->after("slow_moving_item_limit", function ($table){
                $table->string('date_format')->default('DD-MM-YYYY');
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
            $table->dropColumn(['date_format']);
        });
    }
};
