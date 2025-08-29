<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\ApiCallSetting;
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
        Schema::create('psx_api_call_settings', function (Blueprint $table) {
            $table->id();
            $table->integer("delay");
            $table->timestamp('added_date')->nullable();
            $table->timestamp('updated_date')->nullable();
        });

        $apiCallSetting = new ApiCallSetting();
        $apiCallSetting->delay = 3;
        $apiCallSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_api_call_settings');
    }
};
