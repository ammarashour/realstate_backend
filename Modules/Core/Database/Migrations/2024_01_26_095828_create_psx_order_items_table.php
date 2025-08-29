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
        Schema::create('psx_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id");
            $table->foreignId("item_id");
            $table->integer("quantity");
            $table->double("original_price");
            $table->double("sale_price");
            $table->double("sub_total");
            $table->double("discount_price");
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
        Schema::dropIfExists('psx_order_items');
    }
};
