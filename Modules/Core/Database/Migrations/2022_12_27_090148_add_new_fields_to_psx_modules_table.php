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
        Schema::table('psx_modules', function (Blueprint $table) {
            $table->after("sub_menu_id", function ($table){
                $table->tinyInteger("is_not_from_sidebar")->default(0);
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
        Schema::table('psx_modules', function (Blueprint $table) {
            $table->dropColumn(["is_not_from_sidebar"]);
        });
    }
};
