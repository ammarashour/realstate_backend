<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Utilities\CoreField;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_core_field_filter_settings', function (Blueprint $table) {});
        $lat = CoreField::where("field_name", "lat")->first();
        if ($lat) {
            $lat->mandatory = "1";
            $lat->update();
        }
        $lng = CoreField::where("field_name", "lng")->first();
        if ($lng) {
            $lng->mandatory = "1";
            $lng->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_core_field_filter_settings', function (Blueprint $table) {});
    }
};
