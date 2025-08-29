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
            $table->after('token', function($table) {
                $table->boolean('first_time_sync')->default(0);
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
            $table->dropColumn(['first_time_sync']);
        });
    }
};
