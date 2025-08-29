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
            $table->after("title", function ($table){
                $table->tinyInteger("status")->default(0);
                $table->string("route_name")->default('');
                $table->integer("menu_id")->default(0);
                $table->integer("sub_menu_id")->default(0);
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
            $table->dropColumn(["menu_id", "sub_menu_id", "status", "route_name"]);
        });
    }
};
