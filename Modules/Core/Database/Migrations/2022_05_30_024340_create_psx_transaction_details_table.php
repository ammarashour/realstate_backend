<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\Financial\TransactionHeader;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Menu\Module;

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
        //        $module->title = 'transaction_details';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_header_id')->constrained('psx_transaction_headers')->onDelete('cascade');
            $table->foreignId('shop_id');
            $table->foreignId('item_id');
            $table->string('item_name');
            $table->float('price');
            $table->float('original_price');
            $table->float('discount_amount')->nullable();
            $table->float('discount_value')->nullable();
            $table->float('discount_percent')->nullable();
            $table->integer('qty')->default(0);
            $table->string('item_measurement')->nullable();
            $table->string('item_unit')->default('g');
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
        Schema::dropIfExists('psx_transaction_details');
    }
};
