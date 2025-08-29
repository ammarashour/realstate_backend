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
        Schema::table('psx_vendor_modules', function (Blueprint $table) {
            $table->string('id', 30)->primary()->change();
        });

        Schema::table('psx_vendor_menus', function (Blueprint $table) {
            $table->string('module_id', 30)->change();
        });

        Schema::table('psx_vendor_sub_menus', function (Blueprint $table) {
            $table->string('module_id', 30)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
