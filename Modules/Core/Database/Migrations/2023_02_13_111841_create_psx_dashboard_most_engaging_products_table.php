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
        Schema::create('psx_dashboard_most_engaging_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('item_touch_count')->default(0);
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
        Schema::dropIfExists('psx_dashboard_most_engaging_products');
    }
};
