<?php

use Carbon\Carbon;
use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\VendorPayment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\VendorPaymentInfo;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $approvedVendorIds = Vendor::where(Vendor::status, 2)->pluck(Vendor::id);
        foreach($approvedVendorIds as $approvedVendorId){
            VendorPayment::create([
                'payment_id' => 'payment00010',
                'vendor_id' => $approvedVendorId,
                'status' => 1,
                'added_date' => Carbon::now(),
                'added_user_id' => 1
            ]);

            VendorPaymentInfo::create([
                'payment_id' => 'payment00010',
                'core_keys_id' => 'ps-pmt00044',
                'vendor_id' => $approvedVendorId,
                'added_date' => Carbon::now(),
                'added_user_id' => 1
            ]);
            VendorPaymentInfo::create([
                'payment_id' => 'payment00010',
                'core_keys_id' => 'ps-pmt00045',
                'vendor_id' => $approvedVendorId,
                'added_date' => Carbon::now(),
                'added_user_id' => 1
            ]);
            VendorPaymentInfo::create([
                'payment_id' => 'payment00010',
                'core_keys_id' => 'ps-pmt00046',
                'vendor_id' => $approvedVendorId,
                'added_date' => Carbon::now(),
                'added_user_id' => 1
            ]);
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
