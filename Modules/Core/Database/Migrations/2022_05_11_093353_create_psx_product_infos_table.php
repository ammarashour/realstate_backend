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
        Schema::create('psx_product_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->string('core_keys_id');
            $table->string('value')->nullable();
            $table->string('ui_type_id');
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
        Schema::dropIfExists('psx_product_infos');
    }
};
