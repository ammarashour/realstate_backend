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
        Schema::create('psx_cache_keys', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('base_key1');
            $table->string('base_key2')->nullable();
            $table->string('base_key3')->nullable();
            $table->timestamp('added_date');
            $table->timestamp('updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_cache_keys');
    }
};
