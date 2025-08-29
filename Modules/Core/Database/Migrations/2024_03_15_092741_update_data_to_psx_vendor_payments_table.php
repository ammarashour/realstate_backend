<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\VendorPayment;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vendorPayments = VendorPayment::get();
        if($vendorPayments){
            foreach($vendorPayments as $vendorPayment){
                $vendorPayment->status = 1;
                $vendorPayment->update();
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
