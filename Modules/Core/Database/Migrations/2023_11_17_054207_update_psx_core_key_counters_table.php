<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\CoreKeyCounter;
use Modules\Core\Entities\CoreKeyType;
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
        // delete all data except pmt from psx_core_key_counters table
        $coreKeyCounterIds = CoreKeyCounter::where("code", "!=", "pmt")->get()->pluck("id");
        CoreKeyCounter::destroy($coreKeyCounterIds);

        // prepare for counter table except pmt
        $coreKeyTypes = CoreKeyType::where("client_code", "!=", "pmt")->get();
        foreach ($coreKeyTypes as $coreKeyType) {
            $clientCustomizeUiCounter = CustomField::where("core_keys_id", 'like', $coreKeyType->client_code . '%')
                ->where('module_name', $coreKeyType->client_code)
                ->get()->count();

            $newcoreKeysCounter = new CoreKeyCounter();
            $newcoreKeysCounter->code = $coreKeyType->client_code;
            $newcoreKeysCounter->counter = $clientCustomizeUiCounter + 1;
            $newcoreKeysCounter->added_user_id = '1';
            $newcoreKeysCounter->save();
        }
    }

    /**i
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {});
    }
};
