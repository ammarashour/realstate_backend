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
        try {
            $coreFieldFilterSettings = CoreField::where(CoreField::fieldName, 'is_show_email')->first();
            if (!empty($coreFieldFilterSettings)) {
                $coreFieldFilterSettings->enable = 1;
                $coreFieldFilterSettings->update();
            }

            $coreFieldFilterSettings = CoreField::where(CoreField::fieldName, 'is_show_phone')->first();
            if (!empty($coreFieldFilterSettings)) {
                $coreFieldFilterSettings->enable = 1;
                $coreFieldFilterSettings->update();
            }
        } catch (Exception $_) {
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
