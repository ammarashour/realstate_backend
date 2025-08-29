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
        Schema::table('psx_mobile_settings', function (Blueprint $table) {
            $table->after("is_show_subcategory", function ($table){
                $table->tinyInteger("is_show_discount")->default(0);
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
        Schema::table('psx_mobile_settings', function (Blueprint $table) {
            $table->dropColumn(['is_show_subcategory']);
        });
    }
};
