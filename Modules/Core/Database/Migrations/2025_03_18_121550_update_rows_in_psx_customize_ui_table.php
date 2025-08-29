<?php

use App\Config\ps_constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Services\Utilities\ChunkUpdateService;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $project = Project::first();
        if (!$project || $project->base_project_id != 27) {
            return;
        }

        DB::beginTransaction();
        try {
            $this->migrateCustomField();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function migrateCustomField()
    {
        $coreKeyToDelete = 'ps-itm00032';
        $newCoreKey = 'ps-itm00009';

        // Check if record to disable exist
        $toDeleteRecord = DB::table(CustomField::tableName)
            ->where(CustomField::coreKeysId, $coreKeyToDelete)
            ->first();

        // Disable the record
        if ($toDeleteRecord) {
            DB::table(CustomField::tableName)
                ->where(CustomField::coreKeysId, $coreKeyToDelete)
                ->update([
                    CustomField::enable => 0,
                    CustomField::isDelete => 1,
                ]);
        }


        // Check if the record to add already exist
        $existingRecord = DB::table(CustomField::tableName)
            ->where(CustomField::coreKeysId, $newCoreKey)
            ->exists();

        // Add new record
        if (!$existingRecord) {
            $projectId = DB::table(CustomField::tableName)->where(CustomField::uiTypeId, 'uit00006')->pluck(CustomField::projectId)->first();
            $newRecord = [
                CustomField::uiTypeId => 'uit00006',
                CustomField::coreKeysId => $newCoreKey,
                CustomField::mandatory => 0,
                CustomField::isShowSorting => 0,
                CustomField::isShowInFilter => 0,
                CustomField::ordering => 21,
                CustomField::enable => 1,
                CustomField::isDelete => 0,
                CustomField::moduleName => 'itm',
                CustomField::dataType => 'String',
                CustomField::tableId => 227,
                CustomField::projectId => $projectId ?? '128',
                CustomField::projectName => 'PSX Real Estates Classified',
                CustomField::baseModuleName => 'ps-itm',
                CustomField::isIncludeInHideShow => 0,
                CustomField::isShow => 0,
                CustomField::isCoreField => 0,
                CustomField::permissionForEnableDisable => 1,
                CustomField::permissionForDelete => 0,
                CustomField::permissionForMandatory => 1,
                CustomField::addedDate => now(),
                CustomField::addedUserId => 1
            ];
            $insertedId = DB::table(CustomField::tableName)->insertGetId($newRecord);
            $this->updateCustomFieldNames($insertedId, $newCoreKey);
            $this->insertLanguageStrings($insertedId, $newCoreKey);
        }

        $this->updateItemInfoRecords($coreKeyToDelete, $newCoreKey);
    }

    // Update the new inserted custom ui record
    private function updateCustomFieldNames($id, $coreKey)
    {
        DB::table(CustomField::tableName)
            ->where(CustomField::id, $id)
            ->update([
                CustomField::name => "{$coreKey}_{$id}_name",
                CustomField::placeholder => "{$coreKey}_{$id}_placeholder",
            ]);
    }

    // Update Item Info in chunk
    private function updateItemInfoRecords($oldKey, $newKey)
    {
        $chunkUpdateService = new ChunkUpdateService();
        $limit = ps_constant::CHUNK_LIMIT;

        do {
            $itemInfos = DB::table(ItemInfo::tableName)
                ->where(ItemInfo::coreKeysId, $oldKey)
                ->limit($limit)
                ->get();

            if ($itemInfos->isEmpty()) {
                break;
            }

            $bulkUpdateItemInfos = $itemInfos->map(fn($item) => [
                ItemInfo::id => $item->id,
                ItemInfo::coreKeysId => $newKey
            ])->toArray();

            $chunkUpdateService->updateRecordsInChunks($bulkUpdateItemInfos, ItemInfo::tableName, ItemInfo::id);
        } while ($itemInfos->count() === $limit);
    }

    // Insert Records to Language String table
    private function insertLanguageStrings($id, $coreKey)
    {
        $languageIds = DB::table(Language::tableName)->pluck(Language::id, Language::symbol);

        $languageStrings = [
            'name' => [
                'en' => 'Address',
                'ar' => 'عنوان',
                'fr' => 'Adresse',
                'es' => 'Dirección',
                'pt' => 'Endereço',
                'hi' => 'पता',
                'id' => 'Alamat',
                'ja' => '住所',
                'ms' => 'Alamat',
                'ru' => 'Адрес',
                'tr' => 'Adres',
                'de' => 'Adresse',
                'it' => 'Indirizzo',
                'ko' => '주소',
                'th' => 'ที่อยู่',
                'zh' => '地址'
            ],
            'placeholder' => [
                'en' => 'Enter Address',
                'ar' => 'أدخل العنوان',
                'fr' => 'Entrez l’adresse',
                'es' => 'Introduzca la dirección',
                'pt' => 'Insira o endereço',
                'hi' => 'पता दर्ज करें',
                'id' => 'Masukkan Alamat',
                'ja' => '住所を入力してください',
                'ms' => 'Masukkan Alamat',
                'ru' => 'Введите адрес',
                'tr' => 'Adres Girin',
                'de' => 'Adresse eingeben',
                'it' => "Inserisci l'indirizzo",
                'ko' => '주소 입력',
                'th' => 'ป้อนที่อยู่',
                'zh' => '输入地址'
            ]
        ];

        $languageStringRecords = [];
        foreach ($languageIds as $symbol => $languageId) {
            foreach ($languageStrings as $field => $symbolValues) {
                $languageStringRecords[] = [
                    LanguageString::languageId => $languageId,
                    LanguageString::key => "{$coreKey}_{$id}_{$field}",
                    LanguageString::value => $symbolValues[$symbol] ?? $symbolValues['en'],
                    LanguageString::isFromBuilder => 1,
                    LanguageString::addedUserId => 1
                ];
            }
        }

        DB::table(LanguageString::tableName)->insert($languageStringRecords);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback logic if needed
    }
};
