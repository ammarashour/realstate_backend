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
use Modules\Core\Entities\Localization\VendorLanguageString;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Imports\VendorLanguageStringImport;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use Carbon\Carbon;
use Modules\Core\Entities\Localization\CategoryLanguageString;
use Modules\Core\Http\Facades\LanguageFacade;
use Modules\Core\Http\Facades\PsCache;

class VendorLanguageStringService extends PsService implements VendorLanguageStringServiceInterface
{
    
    public function __construct() {
    }

    public function save($vendorLangStringData, $isGenerateLangJson = true)
    {
        DB::beginTransaction();
        try {
            // $this->saveVendorLangaugeStrings($vendorLangStringData);

            // $this->saveStringsInOtherLanguages($vendorLangStringData);

            $languageString = $this->saveVendorLanguageString($vendorLangStringData);
            
            $languages = LanguageFacade::getAll();

            $this->copyVendorLanguageStringToOtherLanguages($languages, $languageString->language_id, $vendorLangStringData);

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

    public function update($languageStringId, $vendorLangStringData, $isGenerateLangJson = true)
    {
        DB::beginTransaction();
        try {
            // $this->updateVendorLanguageString($languageStringId, $vendorLangStringData);
            $languageString = $this->updateVendorLanguageString($languageStringId, $vendorLangStringData);

            // Update language json file
            $fileName = LanguageFacade::get($languageString['language_id'])->symbol;

            if($isGenerateLangJson) {
                PsLanguageJsonHelper::generateJsonFile($fileName, [$languageString]);
            }

            DB::commit();

            PsCache::clear(LocalizationCache::BASE);

        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getAll($languageId = null, $relations = null, $pagPerPage = null, $conds = null, $id = null, $key = null)
    {
        
        // $language = $this->vendorLanguageService->get($languageId);
        
        $languageStrings = VendorLanguageString::when($languageId != null, function ($q) use ($languageId) {
            $q->where(VendorLanguageString::languageId, $languageId);
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                    $q->leftJoin(User::tableName, User::tableName . '.' . User::id, '=', Language::tableName . '.' . $conds['order_by']);
                    $q->select(User::tableName . '.' . User::name . ' as owner', Language::tableName . '.*');
                }
            })
            ->when($id, function ($query, $id){
                $query->where(VendorLanguageString::id, $id);
            })
            ->when($key, function ($query, $key){
                $query->where(VendorLanguageString::key, $key);
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
        $languageStrings = VendorLanguageString::when($id, function ($q, $id) {
            $q->where(VendorLanguageString::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(VendorLanguageString::key, $key);
            })
            ->when($languageId, function ($q, $languageId) {
                $q->where(VendorLanguageString::languageId, $languageId);
            })
            ->first();
        return $languageStrings;
    }

    public function delete($languageId, $languageStringId)
    {
        try {
            // $name = $this->deleteVendorLangString($languageId, $languageStringId);

            $languageString = $this->get($languageStringId);
            
            $this->deleteVendorLanguageStringsByKey($languageString->key);

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
            $this->deleteVendorLanguageStringsByLanguageId($languageId);

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
            $import = new VendorLanguageStringImport(
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

        // $language = $this->vendorLanguageService->get($languageId);
        // $langStrs = $this->getAll($languageId);

        // $language_strings = [];

        // // for col (key, value)
        // $language_strings["key"] = "value";

        // foreach ($langStrs as $language_string) {
        //     $language_strings[$language_string->key] = $language_string->value;
        // }

        // $jsonEncoded = json_encode($language_strings, JSON_UNESCAPED_UNICODE);

        // $file = 'vendor_' . $language->symbol . '_tran.json';

        // file_put_contents($file, $jsonEncoded);

        // return response()->download($file);

        $language = LanguageFacade::get($languageId);

        $langStrs = $this->getAll($languageId);
        
        $language_strings = [
            "key" => "value",
        ] + collect($langStrs)->pluck('value', 'key')->toArray();

        $jsonEncoded = json_encode($language_strings, JSON_UNESCAPED_UNICODE);

        $file = ps_constant::vendorLangStringPrefix . $language->symbol . '_language_strings.json';

        return response()->streamDownload(function () use ($jsonEncoded) {
            echo $jsonEncoded;
        }, $file, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }

    public function exportCSV($languageId)
    {
        // $language = $this->vendorLanguageService->get($languageId);
        // $langStrs = $this->getAll($languageId);

        // $language_strings = "";

        // // for col (key, value)
        // $language_strings .= 'key,value' . "\n";

        // foreach ($langStrs as $language_string) {
        //     $language_strings .= $language_string->key . ',' . $language_string->value . "\n";
        // }
        // $file = 'vendor_' . $language->symbol . '_tran.csv';

        // file_put_contents($file, $language_strings);

        // return response()->download($file);

        $language = LanguageFacade::get($languageId);
        $langStrs = $this->getAll($languageId);

        $language_strings = collect($langStrs)
            ->map(function ($language_string) {
                return $language_string->key . ',' . $language_string->value;
            })
            ->implode("\n");

        $language_strings = "key,value\n" . $language_strings;
        $file = ps_constant::vendorLangStringPrefix . $language->symbol . '_language_strings.csv';

        return response()->streamDownload(function () use ($language_strings, $file) {
            echo $language_strings;
        }, $file, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }

    function generateJsonFilesWithLanguageKeys(array $keys, $toLanguages = []) {
        
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

    function generateJsonFiles($languageId = '')
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
        VendorLanguageString::insert($languageStringData);
        
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
        $prefix = ps_constant::vendorLangStringPrefix)
    {
        // set_time_limit(1500);

        DB::beginTransaction();

        //Refactor Code 3
        try {
            $keys = [];
            $bulkInsertData = [];
            $bulkUpdateData = [];
    
            // Fetch existing keys for each language separately
            $existingKeysPerLanguage = [];
            foreach ($toLanguages as $language) {
                $existingKeysPerLanguage[$language->id] = VendorLanguageString::where('language_id', $language->id)
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
                        'value' => $langStringData['value'],
                        'added_date' => Carbon::now()
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
                    VendorLanguageString::insert($chunk);
                }
            }
    
            // Bulk Update            
            if (!empty($bulkUpdateData)) {
                foreach ($bulkUpdateData as $data) {
                    // Directly update the record
                    VendorLanguageString::where('key', $data['key'])
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

    private function saveVendorLanguageString($vendorLangStringData)
    {
        $language_string = new VendorLanguageString();
        $language_string->fill($vendorLangStringData);
        $language_string->key = handleKey($vendorLangStringData['key'], ps_constant::vendorLangStringPrefix);
        $language_string->added_user_id = Auth::user()->id;
        $language_string->save();

        return $language_string;
    }

    private function copyVendorLanguageStringToOtherLanguages($languages, $targetLanguageId, $langStringData, $prefix = ps_constant::vendorLangStringPrefix)
    {
        $beLangStringKey = handleKey($langStringData['key'], $prefix);

        foreach ($languages as $otherLanguage) {
            if($otherLanguage->id != $targetLanguageId){
            
                VendorLanguageString::firstOrCreate(
                    [
                        VendorLanguageString::key => $beLangStringKey,
                        VendorLanguageString::languageId => $otherLanguage->id,
                    ],
                    [
                        VendorLanguageString::value => $langStringData['value'],
                        VendorLanguageString::addedUserId => Auth::user()->id,                        
                    ]
                );
            }
        }
    }

    private function updateVendorLanguageString($id, $vendorLangStringData)
    {
        $language_string = $this->get($id);
        $language_string->added_user_id = Auth::user()->id;
        $language_string->update($vendorLangStringData);
        
        return $language_string;
    }

    private function deleteVendorLanguageStringsByKey($languageKey)
    {
        
        $languageStringList = $this->getAll(key: $languageKey);
        
        VendorLanguageString::whereIn('id', $languageStringList->pluck('id'))->delete();

    }

    private function deleteVendorLanguageStringsByLanguageId($languageId)
    {
        VendorLanguageString::where('language_id', $languageId)->delete();
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorLanguageString::key, 'like', '%' . $search . '%')
                    ->orWhere(VendorLanguageString::value, 'like', '%' . $search . '%');
            });
        }

        // key
        if (isset($conds['key']) && $conds['key']) {
            $query->where(VendorLanguageString::key, $conds['key']);
        }

        // keys
        if (isset($conds['keys']) && $conds['keys']) {
            $query->whereIn(VendorLanguageString::key, $conds['keys']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(VendorLanguageString::value, 'asc');
        }
        return $query;
    }

}
