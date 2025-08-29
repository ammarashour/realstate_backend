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
        Schema::create('psx_vendor_menu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('group_icon');
            $table->string('group_lang_key');
            $table->tinyInteger('is_show_on_menu');
            $table->tinyInteger('ordering');
            $table->tinyInteger('is_invisible_group_name');
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
        Schema::dropIfExists('psx_vendor_menu_groups');
    }
};
