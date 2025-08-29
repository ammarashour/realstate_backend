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
        //        $module->title = 'location_cities';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_location_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->smallInteger('ordering')->nullable();
            $table->boolean('status')->default(1);
            $table->text('description')->nullable();
            $table->smallInteger('touch_count')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->timestamp('featured_date')->nullable();
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
        Schema::dropIfExists('psx_location_cities');
    }
};
