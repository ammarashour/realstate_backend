<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\BackendSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $backend_setting = BackendSetting::first();

        if (!empty($backend_setting)) {
            $backend_setting->potrait_thumb_height = 200;
            $backend_setting->potrait_thumb2x_height = 400;
            $backend_setting->potrait_thumb3x_height = 700;

            $backend_setting->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
