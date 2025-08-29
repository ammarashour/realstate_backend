<?php

use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;

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
        if($vendors){
            foreach($vendors as $vendor){
                $vendorSetting = new VendorDeliverySetting();
                $vendorSetting->vendor_id = $vendor->id;
                $vendorSetting->added_user_id = 1;
                $vendorSetting->save();
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
        VendorDeliverySetting::truncate();
    }
};
