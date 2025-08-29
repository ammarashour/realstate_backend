<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBilling;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBillingForUser;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_orders', function (Blueprint $table) {
            $table->after('order_code', function($table){
                $table->double("delivery_fee")->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_orders', function (Blueprint $table) {

        });
    }
};
