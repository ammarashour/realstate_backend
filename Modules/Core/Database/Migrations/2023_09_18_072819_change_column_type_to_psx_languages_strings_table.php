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
        Schema::table('psx_language_strings', function (Blueprint $table) {
            $table->String('value', 15000)->change();
        });
        Schema::table('psx_fe_languages_strings', function (Blueprint $table) {
            $table->String('value', 15000)->change();
        });
        Schema::table('psx_mobile_language_strings', function (Blueprint $table) {
            $table->String('value', 15000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_language_strings', function (Blueprint $table) {
            $table->String('value')->change();
        });
        Schema::table('psx_fe_languages_strings', function (Blueprint $table) {
            $table->String('value')->change();
        });
        Schema::table('psx_mobile_language_strings', function (Blueprint $table) {
            $table->String('value')->change();
        });
    }
};
