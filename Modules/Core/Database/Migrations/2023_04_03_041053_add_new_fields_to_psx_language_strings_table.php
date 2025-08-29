<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\LanguageString;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_language_strings', function (Blueprint $table) {
            $table->after("value", function ($table) {
                $table->tinyInteger('is_from_builder')->default(0);
            });
        });


        // to differ builder lang and admin lang
        $getCoreFieldFilterSettings = CoreField::select('label_name', 'placeholder')->get();
        $langForCoreField = collect($getCoreFieldFilterSettings->toArray())->flatten()->all();

        $getCustomizeUis = CustomField::select('name', "placeholder")->get();
        $langForCustomField = collect($getCustomizeUis->toArray())->flatten()->all();

        $langForCoreAndCustomField = collect($langForCoreField)->merge($langForCustomField);

        LanguageString::whereIn("key", $langForCoreAndCustomField)->update(['is_from_builder' => 1]);

        // destroy unused language from builder
        $unUsedLangIds = LanguageString::where([["key", "LIKE", "core_key_%"], ["is_from_builder", "=", 0]])
            ->orWhere([["key", "LIKE", "ps-%"], ["is_from_builder", "=", 0]])
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_language_strings', function (Blueprint $table) {
            $table->dropColumn(['is_from_builder']);
        });
    }
};
