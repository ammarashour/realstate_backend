<?php

namespace Modules\Core\Http\Services\Localization;

use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use App\Http\Contracts\Localization\SubCategoryLanguageServiceInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\SubcategoryLanguageString;

class SubCategoryLanguageService extends PsService implements SubCategoryLanguageServiceInterface
{
    public function __construct(
        protected LanguageServiceInterface $languageService
    ) {}

    public function save($key, $subCategoryId, $values = [])
    {
        DB::beginTransaction();
        try {
            $defaultLanguageString = $this->generateDefaultLanguageString($values);

            foreach ($values as $langString) {
                $languageString = $this->get(null, $key, $langString['language_id'], $subCategoryId);
                if ($languageString) {
                    $languageString->value = $langString['value'];
                    $languageString->added_user_id = Auth::user()->id;
                    $languageString->update();
                } else {
                    $languageString = new SubcategoryLanguageString();
                    $languageString->subcategory_id = $subCategoryId;
                    $languageString->language_id = $langString['language_id'];
                    $languageString->key = $key;
                    $languageString->value = $langString['value'] == '' ? $defaultLanguageString : $langString['value'];
                    $languageString->added_user_id = Auth::id();
                    $languageString->save();
                }

            }
            DB::commit();
            $dataArr = [
                "flag" => 'success',
                "msg" => __('language_strings_updated_success'),

            ];
        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollBack();
            $dataArr = [
                "flag" => 'error',
                'msg' => $e->getMessage(),
            ];
        }

        return $dataArr;
    }

    public function get($id = null, $key = null, $languageId = null, $subCategoryId = null)
    {
        $subCategoryLanguageStrings = SubcategoryLanguageString::when($id, function ($q, $id) {
            $q->where(SubcategoryLanguageString::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(SubcategoryLanguageString::key, $key);
            })
            ->when($languageId, function ($q, $languageId) {
                $q->where(SubcategoryLanguageString::languageId, $languageId);
            })
            ->when($subCategoryId, function ($q, $subCategoryId) {
                $q->where(SubcategoryLanguageString::subcategoryId, $subCategoryId);
            })
            ->first();
        return $subCategoryLanguageStrings;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------    
    private function generateDefaultLanguageString($values)
    {
        $defaultLang = $this->languageService->get(conds : ['status' => 1]);
        
        foreach ($values as $langString) {
            if ($langString['language_id'] == $defaultLang->id && $langString['value'] != '') {
                return $langString['value'];
            }
        }

        foreach ($values as $langString) {
            if ($langString['value'] != '') {
                return $langString['value'];
            }
        }

        return '';
    }

    

}