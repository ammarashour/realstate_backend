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
        Schema::table('psx_core_sub_menu_groups', function (Blueprint $table) {
            $table->after("ordering", function ($table){
                $table->tinyInteger("is_dropdown");
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
        Schema::table('psx_core_sub_menu_groups', function (Blueprint $table) {
            $table->dropColumn(["is_dropdown"]);
        });
    }
};
