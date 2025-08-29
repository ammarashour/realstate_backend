<?php

use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Configuration\MobileSetting;
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
        $mobileSetting = MobileSetting::first();
        if ($mobileSetting) {
            $mobileSetting->version_no = "1.3.0";
            $mobileSetting->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_mobile_settings', function (Blueprint $table) {});
    }
};
