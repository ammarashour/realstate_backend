<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\ItemInfo;
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
        $oldQtyCoreKeysId = "ps-itm00010";
        $newQtyCoreKeysId = "ps-itm00046";
        $nameLangKey = "psc_ps-itm00046_4293_name";
        $placeholderLangKey = "psc_ps-itm00046_4293_placeholder";

        $customizeUi = CustomField::where("core_keys_id", $oldQtyCoreKeysId)->where("table_id", 59)->first();
        if (!empty($customizeUi)) {
            $customizeUi->name = $nameLangKey;
            $customizeUi->placeholder = $placeholderLangKey;
            $customizeUi->core_keys_id = $newQtyCoreKeysId;
            $customizeUi->update();
        }

        $nameLanguageStrings = LanguageString::where("key", "psc_ps-itm00010_4293_name")->get();
        if (!empty($nameLanguageStrings)) {
            foreach ($nameLanguageStrings as $nameLanguageString) {
                $nameLanguageString->key = $nameLangKey;
                $nameLanguageString->update();
            }
        }

        $placeholderLanguageStrings = LanguageString::where("key", "psc_ps-itm00010_4293_placeholder")->get();
        if (!empty($placeholderLanguageStrings)) {
            foreach ($placeholderLanguageStrings as $placeholderLanguageString) {
                $placeholderLanguageString->key = $placeholderLangKey;
                $placeholderLanguageString->update();
            }
        }

        if (!empty($customizeUi)) {
            $itemInfos = ItemInfo::where("core_keys_id", $oldQtyCoreKeysId)->get();
            if (!empty($itemInfos)) {
                foreach ($itemInfos as $itemInfo) {
                    $itemInfo->core_keys_id = $newQtyCoreKeysId;
                    $itemInfo->update();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {});
    }
};
