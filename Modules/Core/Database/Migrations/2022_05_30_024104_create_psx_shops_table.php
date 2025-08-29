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
        //        $module->title = 'shops';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('email')->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->string('cod_email')->nullable();
            $table->boolean('cod_enable')->default(0)->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('payment_status_id')->nullable();
            $table->boolean('is_featured')->default(0)->nullable();
            $table->timestamp('featured_date')->nullable();
            $table->string('overall_tax_label')->nullable();
            $table->float('overall_tax_value')->nullable();
            $table->string('shipping_tax_label')->nullable();
            $table->float('shipping_tax_value')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('terms')->nullable();
            $table->string('facebook')->nullable();
            $table->string('messenger')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->integer('touch_count')->nullable();
            $table->boolean('checkout_with_email')->default(1)->nullable();
            $table->boolean('multi_page_checkout')->default(0)->nullable();
            $table->boolean('checkout_with_whatsapp')->default(0)->nullable();
            $table->float('overall_rating')->nullable();
            $table->float('price_level')->nullable();
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
        Schema::dropIfExists('psx_shops');
    }
};
