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
        //        $module->title = 'system_configs';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_system_configs', function (Blueprint $table) {
            $table->id();
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->boolean('is_approved_enable')->default(1);
            $table->boolean('is_sub_location')->default(1);
            $table->boolean('is_thumb2x_3x_generate')->default(1);
            $table->boolean('is_sub_subscription')->default(1);
            $table->boolean('is_paid_app')->default(1);
            $table->boolean('is_block_user')->default(1);
            $table->integer('max_img_upload_of_item')->default(5);
            $table->foreignId('ad_type')->default(1);
            $table->integer('promo_cell_interval_no')->default(3);
            $table->double('one_day_per_price')->default(2);
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
        Schema::dropIfExists('psx_system_configs');
    }
};
