<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        //        $module->title = 'paid_item_histories';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_paid_item_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('start_timestamp')->nullable();
            $table->timestamp('end_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('end_timestamp')->nullable();
            $table->string('payment_method')->default('NA');
            $table->float('amount');
            $table->integer('promoted_days');
            $table->string('trans_code')->nullable();
            $table->string('razor_id')->nullable();
            $table->string('purchased_id')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('psx_paid_item_histories');
    }
};
