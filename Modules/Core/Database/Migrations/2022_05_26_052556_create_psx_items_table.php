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
        //        $module->title = 'items';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id');
            $table->foreignId('subcategory_id')->nullable();
            $table->foreignId('currency_id');
            $table->foreignId('location_city_id');
            $table->foreignId('location_township_id')->nullable();
            $table->foreignId('shop_id')->nullable();
            $table->float('price');
            $table->float('original_price')->nullable();
            $table->longText('description')->nullable();
            $table->string('search_tag')->nullable();
            $table->string('dynamic_link')->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_paid')->default(0);
            $table->boolean('is_sold_out')->default(0);
            $table->smallInteger('ordering')->nullable();
            $table->boolean('is_available')->default(1);
            $table->string('is_discount')->default(0);
            $table->integer('item_touch_count')->default(0);
            $table->integer('favourite_count')->default(0);
            $table->smallInteger('overall_rating')->default(0);
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
        Schema::dropIfExists('psx_items');
    }
};
