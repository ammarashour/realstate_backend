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
            $table->after("permission_for_mandatory", function ($table){
                $table->foreignId("category_id")->nullable();
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

        });
    }
};
