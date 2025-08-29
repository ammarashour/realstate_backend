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
        Schema::table('psx_system_configs', function (Blueprint $table) {
            $table->after('is_paid_app',function($table){
                $table->boolean('is_promote_enable')->default(1);
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
        Schema::table('psx_system_configs', function (Blueprint $table) {
            $table->dropColumn(['is_promotion_enable']);
        });
    }
};
