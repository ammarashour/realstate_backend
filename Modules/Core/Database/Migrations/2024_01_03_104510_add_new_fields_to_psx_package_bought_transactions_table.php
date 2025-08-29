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
        Schema::table('psx_package_bought_transactions', function (Blueprint $table) {
            $table->after('status', function($table){
                $table->string("transaction_id")->nullable();
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
        Schema::table('psx_package_bought_transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
