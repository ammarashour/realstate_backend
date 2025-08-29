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
            $table->after("map_key", function ($table){

                $table->String('upload_setting')->default('all');

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
            $table->dropColumn(['upload_setting']);
        });
    }
};
