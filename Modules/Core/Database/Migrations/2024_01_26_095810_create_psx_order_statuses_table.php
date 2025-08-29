<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\StoreFront\VendorPanel\Entities\OrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_order_statuses', function (Blueprint $table) {
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
        foreach ($vendors as $vendor) {
            $orderStatuses = [
                [
                    "id" => 1,
                    "name" => "Pending",
                    "description" => "Pending",
                    "colour" => "#F59E0B",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 2,
                    "name" => "Approved",
                    "description" => "Approved",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 3,
                    "name" => "Delivering",
                    "description" => "Delivering",
                    "colour" => "#3B82F6",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 4,
                    "name" => "Delivered",
                    "description" => "Delivered",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 5,
                    "name" => "Cancelled",
                    "description" => "Cancelled",
                    "colour" => "#e600ac",
                    "added_user_id" => 1,
                ],
            ];

            foreach ($orderStatuses as $orderStatus) {
                $orderStatusObj = new OrderStatus();
                $orderStatusObj->id = $orderStatus['id'];
                $orderStatusObj->name = $orderStatus['name'];
                $orderStatusObj->vendor_id = $vendor->id;
                $orderStatusObj->colour = $orderStatus['colour'];
                $orderStatusObj->status = 1;
                $orderStatusObj->is_ps_default = 1;
                $orderStatusObj->description = $orderStatus['description'];
                $orderStatusObj->added_user_id = $orderStatus['added_user_id'];
                $orderStatusObj->save();
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
        Schema::dropIfExists('psx_order_statuses');
    }
};
