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
        Schema::table('psx_core_menu_groups', function (Blueprint $table) {
            $table->after("is_show_on_menu", function ($table){
                $table->tinyInteger("ordering")->default(0);
                $table->tinyInteger("is_invisible_group_name")->default(0);
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
        Schema::table('psx_core_menu_groups', function (Blueprint $table) {
            $table->dropColumn(['ordering']);
            $table->dropColumn(['is_invisible_group_name']);
        });
    }
};
