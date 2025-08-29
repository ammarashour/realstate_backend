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
        Schema::create('psx_shipping_and_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id");
            $table->timestamp("shipping_and_billing_date");
            $table->string("shipping_first_name");
            $table->string("shipping_last_name");
            $table->string("shipping_email");
            $table->string("shipping_phone_no");
            $table->string("shipping_address");
            $table->string("shipping_country");
            $table->string("shipping_state");
            $table->string("shipping_city");
            $table->string("shipping_postal_code");
            $table->tinyInteger("is_save_shipping_info_for_next_time")->default(0);
            $table->string("billing_first_name");
            $table->string("billing_last_name");
            $table->string("billing_email");
            $table->string("billing_phone_no");
            $table->string("billing_address");
            $table->string("billing_country");
            $table->string("billing_state");
            $table->string("billing_city");
            $table->string("billing_postal_code");
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
        Schema::dropIfExists('psx_shipping_and_billings');
    }
};
