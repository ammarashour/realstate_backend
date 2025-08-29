<?php

namespace Modules\Core\Http\Services\Localization;

use App\Http\Services\PsService;
use App\Models\User;
use Modules\Core\Entities\Localization\CategoryLanguageString;
use Modules\Core\Entities\Localization\Language;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Localization\CategoryLanguageStringServiceInterface;
use Modules\Core\Http\Facades\PsCache;
use App\Config\Cache\CategoryCache;

class CategoryLanguageStringService extends PsService implements CategoryLanguageStringServiceInterface
{
    public function __construct(protected LanguageServiceInterface $languageService)
    {}

    public function save($categoryLangStringData)
    {
        DB::beginTransaction();
        try {
            $this->saveCategoryLanguageString($categoryLangStringData);
            
            DB::commit();
            
            PsCache::clear(CategoryCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $categoryLangStringData)
    {
        DB::beginTransaction();
        try {
            $this->updateCategoryLanguageString($id, $categoryLangStringData);

            DB::commit();

            PsCache::clear(CategoryCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAll($languageId, $relations = null, $pagPerPage = null, $conds = null)
    {
        $language = $this->languageService->get($languageId);
        $languageStrings = CategoryLanguageString::where(CategoryLanguageString::languageId, $language->id)
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                    $q->leftJoin(User::tableName, User::tableName . '.' . User::id, '=', Language::tableName . '.' . $conds['order_by']);
                    $q->select(User::tableName . '.' . User::name . ' as owner', Language::tableName . '.*');
                }
            })->when($conds, function ($query, $conds) {
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

    public function get($id = null, $key = null, $languageId = null, $categoryId = null)
    {
        $languageStrings = CategoryLanguageString::when($id, function ($q, $id) {
            $q->where(CategoryLanguageString::id, $id);
        })
            ->when($key, function ($q, $key) {
                $q->where(CategoryLanguageString::key, $key);
            })
            ->when($languageId, function ($q, $languageId) {
                $q->where(CategoryLanguageString::languageId, $languageId);
            })
            ->when($categoryId,function ($q, $categoryId){
                $q->where(CategoryLanguageString::categoryId, $categoryId);
            })
            ->first();
        return $languageStrings;
    }

    public function delete($languageStringId)
    {
        try {
            $name = $this->deleteCategoryLanguageString($languageStringId);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCategoryLanguageString($categoryLangStringData)
    {
        $language_string = new CategoryLanguageString();
        $language_string->fill($categoryLangStringData);
        $language_string->added_user_id = Auth::user()->id;
        $language_string->save();
    }

    private function saveStringInOtherLanguages($categoryLangStringData)
    {
        $languages = $this->languageService->getAll();
        foreach ($languages as $language) {
            if ($language->id !== $categoryLangStringData['language_id']) {
                $language_string = new CategoryLanguageString();
                $language_string->key = $categoryLangStringData['key'];
                $language_string->value = $categoryLangStringData['value'];
                $language_string->language_id = $language->id;
                $language_string->added_user_id = Auth::user()->id;
                $language_string->save();
            }
        }
    }

    private function updateCategoryLanguageString($id, $categoryLangStringData)
    {
        $language_string = $this->get($id);
        $language_string->added_user_id = Auth::user()->id;
        $language_string->update($categoryLangStringData);
    }

    private function deleteCategoryLanguageString($languageStringId)
    {
        $languageString = $this->get($languageStringId);
        $name = $languageString->key;
        $languageString->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(CategoryLanguageString::key, 'like', '%' . $search . '%')
                    ->orWhere(CategoryLanguageString::value, 'like', '%' . $search . '%');
            });
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(CategoryLanguageString::value, 'asc');
        }
        return $query;
    }
}
