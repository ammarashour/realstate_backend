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
        Schema::create('psx_item_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("item_id");
            $table->float("percent");
            $table->float("discount_amount");
            $table->timestamp("exp_date");
            $table->boolean("is_exp_date");
            $table->timestamp('added_date')->nullable();
            $table->foreignId('added_user_id')->nullable();
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
        Schema::dropIfExists('psx_item_discounts');
    }
};
