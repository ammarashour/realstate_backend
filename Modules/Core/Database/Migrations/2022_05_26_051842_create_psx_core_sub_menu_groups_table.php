<?php

use Carbon\Carbon;
use Modules\Core\Entities\Menu\Module;
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
        // add in module table for authorization with policy
        //        $module = new Module();
        //        $module->title = 'core_sub_menu_groups';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_core_sub_menu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('sub_menu_name');
            $table->string('sub_menu_desc');
            $table->string('sub_menu_icon');
            $table->string('sub_menu_lang_key');
            $table->smallInteger('ordering')->nullable();
            $table->boolean('is_show_on_menu')->default(1);
            $table->foreignId('core_menu_group_id')->nullable();
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
        Schema::dropIfExists('psx_core_sub_menu_groups');
    }
};
