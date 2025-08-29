<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Utilities\CustomField;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customField = CustomField::where("module_name", "itm")->where("core_keys_id", "ps-itm00046")->first();
        if(!empty($customField)){
            $customField->mandatory = 0;
            $customField->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optional', function (Blueprint $table) {

        });
    }
};
