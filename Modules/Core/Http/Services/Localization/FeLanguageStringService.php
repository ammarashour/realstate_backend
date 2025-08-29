<?php

namespace Modules\Core\Http\Services\Localization;

use App\Config\Cache\LocalizationCache;
use App\Config\ps_constant;
use App\Enums\Language\JsonGenerationOption;
use App\Helpers\PsLanguageJsonHelper;
use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Localization\FeLanguageString;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Imports\FeLanguageStringImport;
use App\Http\Contracts\Localization\FeLanguageStringServiceInterface;
use Modules\Core\Http\Facades\LanguageFacade;
use Modules\Core\Http\Facades\PsCache;

class FeLanguageStringService extends PsService implements FeLanguageStringServiceInterface
{
    public function __construct()
    {}

    public function save( $langStringData, $isGenerateLangJson = true)
    {
        DB::beginTransaction();
        try {
            $languageString = $this->saveLanguageString($langStringData);
            
            $languages = LanguageFacade::getAll();

            $this->copyFeLanguageStringToOtherLanguages($languages, $languageString->language_id, $langStringData);

            // Update language json files
            if($isGenerateLangJson){
                foreach ($languages as $language) {
                    PsLanguageJsonHelper::generateJsonFile($language->symbol, [$languageString]);
                }
            }

            DB::commit();

            PsCache::clear(LocalizationCache::BASE);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($languageStringId, $langStringData, $isGenerateLangJson = true)
    {
        DB::beginTransaction();
        try {

            $languageString = $this->updateFeLanguageStrings($languageStringId, $langStringData);

            // Update language json file
            $fileName = LanguageFacade::get($languageString['language_id'])->symbol;

            if($isGenerateLangJson) {
                PsLanguageJsonHelper::generateJsonFile($fileName, [$languageString]);
            }

            DB::commit();

            PsCache::clear(LocalizationCache::BASE);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAll($languageId = null, $relations = null, $pagPerPage = null, $conds = null, $id = null, $key = null)
    {
        $languageStrings = FeLanguageString::when($languageId != null, function ($q) use ($languageId) {
            $q->where(FeLanguageString::languageId, $languageId);
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                    $q->leftJoin(User::tableName, User::tableName . '.' . User::id, '=', Language::tableName . '.' . $conds['order_by']);
                    $q->select(User::tableName . '.' . User::name . ' as owner', Language::tableName . '.*');
                }
            })
            ->when($id, function ($query, $id){
                $query->where(FeLanguageString::id, $id);
            })
            ->when($key, function ($query, $key){
                $query->where(FeLanguageString::key, $key);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relations, function ($q, $relations) {
                $q->with($relations);
            })
            ->latest();

        if ($pagPerPage) {
            $languageStrings = $languageStrings->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $languageStrings = $languageStrings->get();
        }
        return $languageStrings;
    }


    public function get($id = null, $key = null, $languageId = null)
    {
        $languageStrings = FeLanguageString::when($id, function ($q, $id) {
            $q->where(FeLanguageString::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(FeLanguageString::key, $key);
            })
            ->when($languageId, function ($q, $languageId) {
                $q->where(FeLanguageString::languageId, $languageId);
            })
            ->first();
        return $languageStrings;
    }

    public function delete($languageId, $languageStringId)
    {
        try {
            $languageString = $this->get($languageStringId);
            
            $this->deleteFrontendLanguageStringsByKey($languageString->key);

            $languages = LanguageFacade::getAll();

            // Delete language json files
            foreach ($languages as $language) {
                PsLanguageJsonHelper::deleteLanguageStringByKeys($language->symbol, [$languageString->key]);
            }

            PsCache::clear(LocalizationCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $languageString->value]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteByLanguageId($languageId)
    {
        try {
            $this->deleteFrontendLanguageStringsByLanguageId($languageId);

            PsCache::clear(LocalizationCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => '']),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function importCSV($languageId, $csvFile)
    {

        try{

            $language = LanguageFacade::get($languageId);
            $import = new FeLanguageStringImport(
                $this, 
                $language, 
                JsonGenerationOption::ALL_LANGUAGE_FILES
            );
            if ($csvFile != null &&
                $csvFile->getSize() > 0) {
                $import->import($csvFile);

                PsCache::clear(LocalizationCache::BASE);
            }
            
        } catch (\Throwable $e) {
            throw $e;
        }

    }

    public function exportJson($languageId)
    {

        $language = LanguageFacade::get($languageId);

        $langStrs = $this->getAll($languageId);
        
        $language_strings = [
            "key" => "value",
        ] + collect($langStrs)->pluck('value', 'key')->toArray();

        $jsonEncoded = json_encode($language_strings, JSON_UNESCAPED_UNICODE);

        $file = ps_constant::feLangStringPrefix . $language->symbol . '_language_strings.json';

        return response()->streamDownload(function () use ($jsonEncoded) {
            echo $jsonEncoded;
        }, $file, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }

    public function exportCSV($languageId)
    {
 
        $language = LanguageFacade::get($languageId);
        $langStrs = $this->getAll($languageId);

        $language_strings = collect($langStrs)
            ->map(function ($language_string) {
                return $language_string->key . ',' . $language_string->value;
            })
            ->implode("\n");

        $language_strings = "key,value\n" . $language_strings;
        $file = ps_constant::feLangStringPrefix . $language->symbol . '_language_strings.csv';

        return response()->streamDownload(function () use ($language_strings, $file) {
            echo $language_strings;
        }, $file, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }


    public function generateJsonFilesWithLanguageKeys(array $keys, $toLanguages = []) {
        
        // if toLanguages is empty, get all languages
        if(empty($toLanguages)) {
            $languages = LanguageFacade::getAll();
        }else {
            $languages = $toLanguages;
        }

        foreach ($languages as $language) {
            $langStrings = $this->getAll(languageId: $language->id, conds: ['keys' => $keys]);
            PsLanguageJsonHelper::generateJsonFile($language->symbol, $langStrings);
        }
    }
    
    public function generateJsonFiles($languageId = '')
    {
        
        $languages = LanguageFacade::getAll(conds: ['id' => $languageId]);
        
        foreach ($languages as $language) {
            $langStringList = $this->getAll($language->id);            
            PsLanguageJsonHelper::generateJsonFile($language->symbol, $langStringList);
        }
    }

    function copyAll($fromLanguage, $toLanguage){
        // Get all from $fromLanguage Strings
        $languageStrings = $this->getAll($fromLanguage->id);

        // Prepare langauge Strings
        foreach ($languageStrings as $languageString) {
            $languageStringData[] = [
                'key' => $languageString->key,
                'value' => $languageString->value,
                'language_id' => $toLanguage->id,
                'added_user_id' => Auth::id(),
            ];
        }

        // Insert to $toLanguage
        FeLanguageString::insert($languageStringData);

        // update json file
        PsLanguageJsonHelper::generateJsonFile($toLanguage->symbol, $this->getAll($toLanguage->id));
    }

    /**
     * $toLanguages : array of language that we want to import
     * $langStrings : array of language strings to import to $toLanguages
     * $targetLanguage : if set, import all $langStrings to $targetLanguage 
     *                      and create new strings in $toLanguages if not exist.
     * $prefix : prefix for key                      
     */
    public function importLanguageStrings(
        $toLanguages, 
        $langStrings, 
        $targetLanguage = null, 
        $prefix = ps_constant::feLangStringPrefix)
    {
        DB::beginTransaction();

        try {
            $keys = [];
            $bulkInsertData = [];
            $bulkUpdateData = [];
    
            // Fetch existing keys for each language separately
            $existingKeysPerLanguage = [];
            foreach ($toLanguages as $language) {
                $existingKeysPerLanguage[$language->id] = FeLanguageString::where('language_id', $language->id)
                    ->pluck('key') // Fetch only keys for this language
                    ->flip(); // Convert keys into an associative array for quick lookup
            }
    
            foreach ($toLanguages as $language) {
                foreach ($langStrings as $langStringData) {
                    $langStringData['key'] = str_replace(' ', '', $langStringData['key']);
                    $key = handleKey($langStringData['key'], $prefix);
    
                    if (!in_array($key, $keys)) {
                        $keys[] = $key;
                    }
    
                    $data = [
                        'key' => $key,
                        'language_id' => $language->id,
                        'added_user_id' => Auth::user()->id,
                        'value' => $langStringData['value']
                    ];
    
                    // Check if the key already exists for this language
                    $exists = isset($existingKeysPerLanguage[$language->id][$key]);
                    
                    if ($targetLanguage === null || $targetLanguage->id == $language->id) {
                        if ($exists) {
                            $bulkUpdateData[] = $data;
                        } else {
                            $bulkInsertData[] = $data;
                        }
                    } else {
                        // Only insert if key doesn't exist
                        if (!$exists) {
                            $bulkInsertData[] = $data;
                        }
                    }
                }
            }
    
            // Bulk Insert (Only new entries)
            if (!empty($bulkInsertData)) {
                $bulkInsertData = $this->removeDuplicateKeys($bulkInsertData);

                $batchSize = 1000; // Adjust the batch size based on your server's capacity
                $bulkInsertData = array_chunk($bulkInsertData, $batchSize);

                foreach ($bulkInsertData as $chunk) {
                    FeLanguageString::insert($chunk);
                }
            }
    
            // Bulk Update            
            if (!empty($bulkUpdateData)) {
                foreach ($bulkUpdateData as $data) {
                    // Directly update the record
                    FeLanguageString::where('key', $data['key'])
                        ->where('language_id', $data['language_id'])
                        ->update([
                            'value' => $data['value'],
                            'added_user_id' => $data['added_user_id'],
                            'updated_user_id' => Auth::user()->id
                        ]);
                }
            }
            
    
            DB::commit();
    
            PsCache::clear(LocalizationCache::BASE);
    
            return array_values($keys);
    
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------
    private function removeDuplicateKeys($array) {
        $result = [];
        foreach ($array as $data) {
            $result[$data['key'] . $data['language_id']] = $data;
        }
        // Return only the values of the result array
        return array_values($result);
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveLanguageString($langStringData)
    {
        $feLangString = new FeLanguageString();
        $feLangString->fill($langStringData);
        $feLangString->key = handleKey($langStringData['key'], ps_constant::feLangStringPrefix);
        $feLangString->added_user_id = Auth::user()->id;
        $feLangString->save();

        return $feLangString;
    }

    private function copyFeLanguageStringToOtherLanguages($languages, $targetLanguageId, $langStringData, $prefix = ps_constant::feLangStringPrefix)
    {
        $beLangStringKey = handleKey($langStringData['key'], $prefix);

        foreach ($languages as $otherLanguage) {
            if($otherLanguage->id != $targetLanguageId){
            
                FeLanguageString::firstOrCreate(
                    [
                        FeLanguageString::key => $beLangStringKey,
                        FeLanguageString::languageId => $otherLanguage->id,
                    ],
                    [
                        FeLanguageString::value => $langStringData['value'],
                        FeLanguageString::addedUserId => Auth::user()->id,                        
                    ]
                );
            }
        }
    }

    private function updateFeLanguageStrings($id, $langStringData)
    {
        $langString = $this->get($id);
        $langString->updated_user_id = Auth::user()->id;
        $langString->update($langStringData);
        
        return $langString;
    }

    private function deleteFrontendLanguageStringsByKey($languageKey)
    {
        
        $languageStringList = $this->getAll(key: $languageKey);
        
        FeLanguageString::whereIn('id', $languageStringList->pluck('id'))->delete();

    }

    private function deleteFrontendLanguageStringsByLanguageId($languageId)
    {
        FeLanguageString::where('language_id', $languageId)->delete();
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(FeLanguageString::key, 'like', '%' . $search . '%')
                    ->orWhere(FeLanguageString::value, 'like', '%' . $search . '%');
            });
        }

        // key
        if (isset($conds['key']) && $conds['key']) {
            $query->where(FeLanguageString::key, $conds['key']);
        }

        // keys
        if (isset($conds['keys']) && $conds['keys']) {
            $query->whereIn(FeLanguageString::key, $conds['keys']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(FeLanguageString::value, 'asc');
        }
        return $query;
    }

}

