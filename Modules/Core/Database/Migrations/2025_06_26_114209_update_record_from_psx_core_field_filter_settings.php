<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        $coreField = CoreField::where(CoreField::fieldName, 'original_price')->first();
        if($coreField) {
            $coreField->update([
                CoreField::mandatory => 1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $coreField = CoreField::where(CoreField::fieldName, 'original_price')->first();
        if($coreField) {
            $coreField->update([
                CoreField::mandatory => 0
            ]);
        }
    }
};
