<?php

use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\Payment;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\VendorPayment;
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
        $vendors = Vendor::where(Vendor::status, 2)->get();
        $removeIds = [Constants::offlinePaymentId, Constants::promotionInAppPurchasePaymentId, Constants::packageInAppPurchasePaymentId, "payment00008"];
        $payments = Payment::whereNotIn(Payment::id, $removeIds)->get();

        if(!$vendors->isEmpty()){
            foreach($vendors as $vendor){
                foreach($payments as $payment){
                    $vendorPayment = new VendorPayment();
                    $vendorPayment->payment_id = $payment->id;
                    $vendorPayment->vendor_id = $vendor->id;
                    $vendorPayment->status = $payment->id == Constants::paypalPaymentId ? 1 : 0;
                    $vendorPayment->added_user_id = 1;
                    $vendorPayment->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_vendor_payments', function (Blueprint $table) {

        });
    }
};
