<?php

use Carbon\Carbon;
use Modules\Core\Entities\Menu\Module;
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
        // add in module table for authorization with policy
        //        $module = new Module();
        //        $module->title = 'transaction_headers';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_transaction_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('shop_id');
            $table->float('sub_total_amount');
            $table->float('discount_amount')->default(0);
            $table->float('coupon_discount_amount')->default(0);
            $table->float('tax_amount')->default(0);
            $table->float('tax_percent')->default(0);
            $table->float('shipping_amount')->default(0);
            $table->float('shipping_tax_percent')->default(0);
            $table->float('balance_amount')->default(0);
            $table->float('total_item_amount')->default(0);
            $table->float('total_item_count')->default(0);
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->foreignId('transaction_status_id')->nullable();
            $table->foreignId('payment_status_id')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('currency_short_form')->nullable();
            $table->string('trans_code')->nullable();
            $table->string('memo')->nullable();
            $table->float('trans_lat', 10, 6)->nullable();
            $table->float('trans_lng', 10, 6)->nullable();
            $table->float('shipping_method_amount')->nullable();
            $table->string('shipping_method_name')->nullable();
            $table->foreignId('shipping_id')->nullable(); // for contact_area_id
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('billing_address_1')->nullable();
            $table->string('billing_address_2')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address_1')->nullable();
            $table->string('shipping_address_2')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->timestamp('added_date');
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
        Schema::dropIfExists('psx_transaction_headers');
    }
};
