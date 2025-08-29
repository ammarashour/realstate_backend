<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Item;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_order_items', function (Blueprint $table) {
            $table->after('order_id', function($table){
                $table->string("item_name");
            });
        });

        // for dev only
        // $orderItems = OrderItem::all();
        // foreach($orderItems as $orderItem){
        //     $item = Item::where("id", $orderItem->item_id)->first();
        //     if(!empty($item)){
        //         $orderItem->item_name = $item->title;
        //         $orderItem->update();
        //     }
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_order_items', function (Blueprint $table) {

        });
    }
};
