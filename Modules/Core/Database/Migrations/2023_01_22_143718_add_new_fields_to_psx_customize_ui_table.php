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
        Schema::table('psx_customize_ui', function (Blueprint $table) {
            $table->after("mandatory", function ($table){
                $table->tinyInteger("is_show_sorting")->default(0);
                $table->tinyInteger("is_show_in_filter")->default(0);
                $table->integer("ordering");
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
        Schema::table('psx_customize_ui', function (Blueprint $table) {
            $table->dropColumn(['is_show_sorting', 'is_show_in_filter', 'ordering']);
        });
    }
};
