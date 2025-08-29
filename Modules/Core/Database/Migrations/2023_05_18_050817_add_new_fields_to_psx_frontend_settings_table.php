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
            $table->after("app_store_url", function ($table){
                $table->tinyInteger('is_demo_for_payment')->default(0)->nullable();
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
            $table->dropColumn([
                'is_demo_for_payment',
            ]);
        });
    }
};
