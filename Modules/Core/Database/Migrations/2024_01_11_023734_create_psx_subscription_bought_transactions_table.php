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
        Schema::create('psx_subscription_bought_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('vendor_id');
            $table->foreignId('subscription_plan_id');
            $table->string('payment_method')->default('N.A');
            $table->double('price')->nullable();
            $table->string('razor_id')->nullable();
            $table->boolean('is_paystack')->nullable();
            $table->boolean('status')->default(1);
            $table->string('transaction_id');
            $table->timestamp('added_date');
            $table->timestamp('expired_date')->nullable();
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_subscription_bought_transactions');
    }
};
