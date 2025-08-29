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
        Schema::table('psx_projects', function (Blueprint $table) {
            $table->after("project_name", function ($table){
                $table->longText("ps_license_code")->nullable();
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
        Schema::table('psx_projects', function (Blueprint $table) {
            $table->dropColumn(['ps_license_code']);
        });
    }
};
