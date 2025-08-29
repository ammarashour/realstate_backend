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
        Schema::table('psx_frontend_settings', function (Blueprint $table) {
           $table->after('copyright',function($table){
            $table->string('frontend_meta_title');
            $table->longText('frontend_meta_description');
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
        Schema::table('frontend_settings', function (Blueprint $table) {

        });
    }
};
