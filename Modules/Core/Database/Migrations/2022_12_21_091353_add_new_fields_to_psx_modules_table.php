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
               $table->string("lang_key")->nullable();
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
            $table->dropColumn(['lang_key']);
        });
    }
};
