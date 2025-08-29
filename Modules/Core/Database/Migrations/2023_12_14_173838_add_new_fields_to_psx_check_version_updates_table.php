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
        Schema::table('psx_check_version_updates', function (Blueprint $table) {
            $table->after('mobile_language_version_code', function($table){
                $table->string("vendor_language_version_number")->default(0);
                $table->string("vendor_language_version_code")->default(0);
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
        Schema::table('psx_check_version_updates', function (Blueprint $table) {

        });
    }
};
