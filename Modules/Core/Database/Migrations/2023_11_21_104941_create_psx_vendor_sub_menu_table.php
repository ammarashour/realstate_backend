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
        Schema::create('psx_vendor_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->string('sub_menu_name');
            $table->string('sub_menu_desc');
            $table->foreignId('icon_id');
            $table->string('sub_menu_lang_key');
            $table->tinyInteger('is_show_on_menu');
            $table->tinyInteger('ordering');
            $table->foreignId('module_id')->nullable();
            $table->foreignId('core_menu_group_id');
            $table->tinyInteger('is_dropdown');
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
        Schema::dropIfExists('psx_vendor_sub_menus');
    }
};
