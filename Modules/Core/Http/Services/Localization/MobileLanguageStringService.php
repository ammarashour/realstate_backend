<?php

namespace Modules\Core\Http\Services\Localization;

use App\Config\ps_constant;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Localization\MobileLanguageString;
use Modules\Core\Http\Facades\MobileLanguageFacade;
use Modules\Core\Imports\MobileLanguageStringImport;


class MobileLanguageStringService extends PsService implements MobileLanguageStringServiceInterface
{
    
    public function __construct() {        
    }

    public function save($mbLangStringData)
    {
        DB::beginTransaction();

        try {
            
            $languageString = $this->saveMbLanguageString($mbLangStringData);
            
            $languages = MobileLanguageFacade::getAll();

            $this->copyMbLanguageStringToOtherLanguages($languages, $languageString->mobile_language_id, $mbLangStringData);

            foreach($languages as $language){
                $this->updateCode($language->id);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($mobileLanguageStringId, $mbLangStringData)
    {
        DB::beginTransaction();

        try {

            $updatedLanguage = $this->updateMbLanguageString($mobileLanguageStringId, $mbLangStringData);

            $this->updateCode($updatedLanguage->mobile_language_id);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function get($id = null, $key = null, $languageId = null)
    {
        $mobileLanguageString = MobileLanguageString::when($id, function ($q, $id) {
            $q->where(MobileLanguageString::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(MobileLanguageString::key, $key);
            })
            ->when($languageId, function ($q, $languageId) {
                $q->where(MobileLanguageString::mobileLanguageId, $languageId);
            })
            ->first();
        return $mobileLanguageString;
    }

    public function getAll($mobileLanguageId = null, $relation = null, $limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null, $key = null)
    {
        $languageStrings = MobileLanguageString::when($mobileLanguageId != null, function ($q) use ($mobileLanguageId) {
            $q->where(MobileLanguageString::mobileLanguageId, $mobileLanguageId);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest();

        if ($pagPerPage) {
            $languageStrings = $languageStrings->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $languageStrings = $languageStrings->get();
        } else {
            $languageStrings = $languageStrings->get();
        }
        return $languageStrings;
    }

    public function delete($mobileLanguageId, $mobileLanguageStringId)
    {
        try {
            
            $languageString = $this->get($mobileLanguageStringId);
            
            $this->deleteMobileLanguageStringsByKey($languageString->key);

            $this->updateCode($languageString->mobile_language_id);

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
            $this->deleteMobileLanguageStringsByLanguageId($languageId);

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
            $language = MobileLanguageFacade::get($languageId);
            $import = new MobileLanguageStringImport(
                $this, 
                $language
            );
            if ($csvFile != null &&
                $csvFile->getSize() > 0) {
                $import->import($csvFile);                
            }
            
        } catch (\Throwable $e) {
            throw $e;
        }

        
    }

    public function exportJson($languageId)
    {
        $language = MobileLanguageFacade::get($languageId);

        $langStrs = $this->getAll($languageId);
        
        $language_strings = [
            "key" => "value",
        ] + collect($langStrs)->pluck('value', 'key')->toArray();

        $jsonEncoded = json_encode($language_strings, JSON_UNESCAPED_UNICODE);

        $file = 'mb__' . $language->symbol . '_language_strings.json';

        return response()->streamDownload(function () use ($jsonEncoded) {
            echo $jsonEncoded;
        }, $file, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }

    public function exportCSV($languageId)
    {
        
        $language = MobileLanguageFacade::get($languageId);
        $langStrs = $this->getAll($languageId);

        $language_strings = collect($langStrs)
            ->map(function ($language_string) {
                return $language_string->key . ',' . $language_string->value;
            })
            ->implode("\n");

        $language_strings = "key,value\n" . $language_strings;
        $file = 'mb__' . $language->symbol . '_language_strings.csv';

        return response()->streamDownload(function () use ($language_strings, $file) {
            echo $language_strings;
        }, $file, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$file",
        ]);
    }

    public function updateCode($languageId)
    {
        // update code for mobile language start
        $mobile_language = MobileLanguageFacade::get($languageId);
        $mobile_language->code = Carbon::now()->getPreciseTimestamp(3);
        $mobile_language->update();
        // update code for mobile language end
    }

    public function copyAll($fromLanguage, $toLanguage){
        // Get all from $fromLanguage Strings
        $languageStrings = $this->getAll($fromLanguage->id);

        // Prepare langauge Strings
        foreach ($languageStrings as $languageString) {
            $languageStringData[] = [
                'key' => $languageString->key,
                'value' => $languageString->value,
                'mobile_language_id' => $toLanguage->id,
                'added_user_id' => Auth::id(),
            ];
        }

        // Insert to $toLanguage
        MobileLanguageString::insert($languageStringData);
        
        $this->updateCode($toLanguage->id);
    }

    /**
     * $toLanguages : array of language that we want to import
     * $langStrings : array of language strings to import to $toLanguages
     * $targetLanguage : if set, import all $langStrings to $targetLanguage 
     *                      and create new strings in $toLanguages if not exist.                         
     */
    public function importLanguageStrings(
        $toLanguages, 
        $langStrings, 
        $targetLanguage = null)
    {

        DB::beginTransaction();

        try {
            $keys = [];
            $bulkInsertData = [];
            $bulkUpdateData = [];
    
            // Fetch existing keys for each language separately
            $existingKeysPerLanguage = [];
            foreach ($toLanguages as $language) {
                $existingKeysPerLanguage[$language->id] = MobileLanguageString::where('mobile_language_id', $language->id)
                    ->pluck('key') // Fetch only keys for this language
                    ->flip(); // Convert keys into an associative array for quick lookup
            }
    
            foreach ($toLanguages as $language) {
                foreach ($langStrings as $langStringData) 
                {
                    $key = str_replace(' ', '', $langStringData['key']);
    
                    if (!in_array($key, $keys)) {
                        $keys[] = $key;
                    }
    
                    $data = [
                        'key' => $key,
                        'mobile_language_id' => $language->id,
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
                    MobileLanguageString::insert($chunk);
                }
            }
    
            // Bulk Update            
            if (!empty($bulkUpdateData)) {
                foreach ($bulkUpdateData as $data) {
                    // Directly update the record
                    MobileLanguageString::where('key', $data['key'])
                        ->where('mobile_language_id', $data['mobile_language_id'])
                        ->update([
                            'value' => $data['value'],
                            'added_user_id' => $data['added_user_id'],
                            'updated_user_id' => Auth::user()->id
                        ]);
                }
            }
            
    
            DB::commit();
    
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
            $result[$data['key'] . $data['mobile_language_id']] = $data;
        }
        // Return only the values of the result array
        return array_values($result);
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveMbLanguageString($mbLangStringData)
    {
        $mobile_language_string = new MobileLanguageString();
        $mobile_language_string->fill($mbLangStringData);
        $mobile_language_string->added_user_id = Auth::user()->id;
        $mobile_language_string->save();

        return $mobile_language_string;
    }

    private function copyMbLanguageStringToOtherLanguages($languages, $targetLanguageId, $langStringData)
    {
        foreach ($languages as $otherLanguage) {
            if($otherLanguage->id != $targetLanguageId){
            
                MobileLanguageString::firstOrCreate(
                    [
                        MobileLanguageString::key => $langStringData['key'],
                        MobileLanguageString::mobileLanguageId => $otherLanguage->id,
                    ],
                    [
                        MobileLanguageString::value => $langStringData['value'],
                        MobileLanguageString::addedUserId => Auth::user()->id,
                    
                    ]
                );
            }
        }
    }

    private function updateMbLanguageString($id, $mbLangStringData)
    {
        $mobile_language_string = $this->get($id);
        $mobile_language_string->updated_user_id = Auth::user()->id;
        $mobile_language_string->update($mbLangStringData);

        return $mobile_language_string;
    }

    private function deleteMobileLanguageStringsByKey($languageKey)
    {
        
        $languageStringList = $this->getAll(conds: [ 'key' => $languageKey ]);
        
        MobileLanguageString::whereIn('id', $languageStringList->pluck('id'))->delete();

    }

    private function deleteMobileLanguageStringsByLanguageId($languageId)
    {
        MobileLanguageString::where('mobile_language_id', $languageId)->delete();
    }

    private function searching($query, $conds)
    {
        // search by keyword

        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }

        // key
        if (isset($conds['key']) && $conds['key']) {
            $query->where(MobileLanguageString::key, $conds['key']);
        }

        // keys
        if (isset($conds['keys']) && $conds['keys']) {
            $query->whereIn(MobileLanguageString::key, $conds['keys']);
        }

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(MobileLanguageString::tableName . '.' . MobileLanguageString::key, 'like', '%' . $search . '%')
                    ->orWhere(MobileLanguageString::tableName . '.' . MobileLanguageString::value, 'like', '%' . $search . '%');
            });
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(MobileLanguageString::tableName . '.id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(MobileLanguageString::tableName . '.status', 'desc')->orderBy(MobileLanguageString::tableName . '.value', 'asc');
        }

        return $query;
    }

}
