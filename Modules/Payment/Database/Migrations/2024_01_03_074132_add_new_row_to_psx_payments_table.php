<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $payment = new Payment();
        $payment->id = "payment00008";
        $payment->name = "Vendor Subscription Plan";
        $payment->description = "Vendor Subscription Plan";
        $payment->status = 1;
        $payment->added_date = now();
        $payment->added_user_id = 1;
        $payment->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_payments', function (Blueprint $table) {

        });
    }
};
