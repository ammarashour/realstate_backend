<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->after('app_store_url',function($table){
                $table->string('facebook_url')->nullable();
                $table->string('linkedin_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('pinterest_url')->nullable();
                $table->string('youtube_url')->nullable();
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
        Schema::table('psx_frontend_settings', function (Blueprint $table) {
            //
        });
    }
};
