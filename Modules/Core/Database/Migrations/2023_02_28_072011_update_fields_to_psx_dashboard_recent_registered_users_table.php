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
        Schema::table('psx_dashboard_recent_registered_users', function (Blueprint $table) {
            $table->string('user_phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_dashboard_recent_registered_users', function (Blueprint $table) {
            $table->string('user_phone')->nullable(false)->change();
        });
    }
};
