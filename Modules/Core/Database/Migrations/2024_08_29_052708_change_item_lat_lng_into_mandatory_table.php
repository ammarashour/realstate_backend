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
        $lat = CoreField::where(CoreField::fieldName, "lat")->where(CoreField::moduleName, "itm")->first();
        $lat->mandatory = 1;
        $lat->update();

        $lng = CoreField::where(CoreField::fieldName, "lng")->where(CoreField::moduleName, "itm")->first();
        $lng->mandatory = 1;
        $lng->update();
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
