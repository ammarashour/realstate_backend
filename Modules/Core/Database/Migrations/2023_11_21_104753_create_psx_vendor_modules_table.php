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
        Schema::create('psx_vendor_modules', function (Blueprint $table) {
            $table->string('id');
            $table->string('title');
            $table->string('lang_key');
            $table->foreignId('menu_id')->nullable();
            $table->foreignId('sub_menu_id')->nullable();
            $table->tinyInteger('is_not_from_sidebar');
            $table->string('status');
            $table->string('route_name');
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_vendor_modules');
    }
};
