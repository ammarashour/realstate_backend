<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Menu\Module;

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
        //        $module->title = 'available_currencies';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_available_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_symbol');
            $table->string('currency_short_form');
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('psx_available_currencies');
    }
};
