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
        Schema::table('psx_landing_pages', function (Blueprint $table) {
            $table->after("description", function ($table){
                $table->string('default_landing_page_color')->default('#A92428')->nullable();
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
        Schema::table('psx_landing_pages', function (Blueprint $table) {
            $table->dropColumn([
                'default_landing_page_color',
            ]);
        });
    }
};
