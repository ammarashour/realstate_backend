<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        //        $module->title = 'chat_histories';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_chat_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreignId('buyer_user_id');
            $table->foreignId('seller_user_id');
            $table->double('nego_price')->default(0)->nullable();
            $table->integer('buyer_unread_count')->default(0)->nullable();
            $table->integer('seller_unread_count')->default(0)->nullable();
            $table->string('latest_chat_message')->nullable();
            $table->boolean('is_accept')->default(0);
            $table->boolean('offer_status')->default(0);
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
        Schema::dropIfExists('psx_chat_histories');
    }
};
