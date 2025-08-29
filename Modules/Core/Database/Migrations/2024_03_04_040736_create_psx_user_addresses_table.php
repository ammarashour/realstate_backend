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
        Schema::create('psx_user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email");
            $table->string("phone_no");
            $table->string("address");
            $table->string("country");
            $table->string("state");
            $table->string("city");
            $table->string("postal_code");
            $table->tinyInteger("is_save_shipping_info_for_next_time")->default(0);
            $table->tinyInteger("is_save_billing_info_for_next_time")->default(0);
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
        Schema::dropIfExists('psx_user_addresses');
    }
};
