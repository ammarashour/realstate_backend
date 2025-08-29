<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\StoreFront\VendorPanel\Entities\VendorPaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_vendor_payment_statuses', function (Blueprint $table) {
            $table->integer('id');
            $table->string("name");
            $table->string("description");
            $table->string("colour")->default("#000000");
            $table->foreignId("vendor_id");
            $table->tinyInteger("status")->default(1);
            $table->tinyInteger("is_ps_default")->default(0);
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });

        $vendors = Vendor::all();
        foreach($vendors as $vendor){
            $vendorPaymentStatuses = [
                [
                    "id" => 1,
                    "name" => "Unpaid",
                    "description" => "Unpaid",
                    "colour" => "#DC2626",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 2,
                    "name" => "Paid",
                    "description" => "Paid",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 3,
                    "name" => "Rejected by Vendor",
                    "description" => "Rejected by Vendor",
                    "colour" => "#cc0081",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 4,
                    "name" => "Refunded",
                    "description" => "Refunded",
                    "colour" => "#3600cc",
                    "added_user_id" => 1,
                ],
            ];

            foreach($vendorPaymentStatuses as $vendorPaymentStatus){
                $vendorPaymentStatusObj = new VendorPaymentStatus();
                $vendorPaymentStatusObj->id = $vendorPaymentStatus['id'];
                $vendorPaymentStatusObj->name = $vendorPaymentStatus['name'];
                $vendorPaymentStatusObj->vendor_id = $vendor->id;
                $vendorPaymentStatusObj->status = 1;
                $vendorPaymentStatusObj->colour = $vendorPaymentStatus['colour'];
                $vendorPaymentStatusObj->is_ps_default = 1;
                $vendorPaymentStatusObj->description = $vendorPaymentStatus['description'];
                $vendorPaymentStatusObj->added_user_id = $vendorPaymentStatus['added_user_id'];
                $vendorPaymentStatusObj->save();
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
        Schema::dropIfExists('psx_vendor_payment_statuses');
    }
};
