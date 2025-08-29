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
        Schema::create('psx_vendor_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id");
            $table->foreignId("currency_id");
            $table->foreignId("user_id");
            $table->foreignId("vendor_id");
            $table->timestamp("payment_date")->useCurrent();
            $table->double("payment_amount");
            $table->string("payment_method")->default("N.A");
            $table->string('razor_id')->nullable();
            $table->boolean('is_paystack')->nullable();
            $table->foreignId("vendor_payment_status_id")->default(0);
            $table->string("transaction_id")->nullable();
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
        Schema::dropIfExists('psx_vendor_transactions');
    }
};
