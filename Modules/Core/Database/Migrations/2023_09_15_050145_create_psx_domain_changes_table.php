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
        Schema::create('psx_domain_changes', function (Blueprint $table) {
            $table->id();
            $table->String('domain_name')->default('https://www.products.panacea-soft.co/');
            $table->String('sub_folder')->default('psx-mpc-demo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_domain_changes');
    }
};
