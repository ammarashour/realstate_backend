<?php

namespace Modules\Core\Http\Services\Category;

use App\Config\Cache\SubcategoryCache;
use App\Http\Contracts\Category\SubcategoryServiceInterface;
use App\Http\Contracts\Configuration\CoreKeyCounterServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Localization\SubCategoryLanguageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Category\Subcategory;
use Modules\Core\Entities\SubcategoryLanguageString;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Imports\SubcategoryImport;

class SubcategoryService extends PsService implements SubcategoryServiceInterface
{
    public function __construct(
        protected ImageServiceInterface $imageService,
        protected SubCategoryLanguageServiceInterface $subCategoryLanguageService,
        protected CoreKeyCounterServiceInterface $coreKeyCounterService,
        protected LanguageServiceInterface $languageService) {}

    public function save($subcategoryData, $subcategoryImage, $subcategoryIcon)
    {
        DB::beginTransaction();
        try {
            // save subcategory
            $subcategory = $this->saveSubcategory($subcategoryData);

            // save subcategory cover photo
            $imgData = $this->prepareSaveImageData($subcategory->id);
            $this->imageService->save($subcategoryImage, $imgData);

            // save subcategory icon photo
            $iconImgData = $this->prepareSaveIconData($subcategory->id);
            $this->imageService->save($subcategoryIcon, $iconImgData);

            //generate langugae string
            $this->generateSubCategoryLanguageString(languageString : $subcategoryData['nameForm'],
                subCategoryNameKey : $subcategory->name,
                subCategoryId : $subcategory->id);

            DB::commit();

            PsCache::clear(SubcategoryCache::BASE);

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $subcategoryData, $subcategoryImageId, $subcategoryImage, $subcategoryIconId, $subcategoryIcon)
    {
        DB::beginTransaction();
        try {
            // update subcategory
            $subcategory = $this->updateSubcategory($id, $subcategoryData);

            // update subcategory cover photo
            $imgData = $this->prepareSaveImageData($id);
            $this->imageService->update($subcategoryImageId, $subcategoryImage, $imgData);

            // update subcategory icon photo
            $iconImgData = $this->prepareSaveIconData($id);
            $this->imageService->update($subcategoryIconId, $subcategoryIcon, $iconImgData);

            //generate langugae string
            $this->generateSubCategoryLanguageString(languageString : $subcategoryData['nameForm'],
            subCategoryNameKey : $subcategory->key,
            subCategoryId : $subcategory->id);

            DB::commit();

            PsCache::clear(SubcategoryCache::BASE);

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->imageService->deleteAll($id, Constants::subcategoryCoverImgType);

            $name = $this->deleteSubcategory($id);

            $this->delectSubCategoryLanguages($id);

            PsCache::clear(SubcategoryCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $name = null, $languageId = null, $relation = null, $conds = null)
    {
        $langConds = $this->prepareLanguageData();
        $activeLanguage = $this->languageService->get(null, $langConds);
        $langId = $languageId ?? $activeLanguage->id;

        $param = [$id, $name, $relation, $languageId];

        return PsCache::remember([SubcategoryCache::BASE], SubcategoryCache::GET_EXPIRY, $param,
            function() use($id, $relation, $langId, $conds){
                return Subcategory::leftjoin(SubcategoryLanguageString::tableName, function ($query) use ($langId) {
                    $query->on(Subcategory::t(Subcategory::name), '=', SubcategoryLanguageString::tableName.'.'.SubcategoryLanguageString::key)
                        ->where(SubcategoryLanguageString::tableName.'.'.SubcategoryLanguageString::languageId, $langId);
                })
                    ->join(Category::tableName, Category::t(Category::id), '=', Subcategory::t(Subcategory::categoryId))
                    ->select(Subcategory::t(Subcategory::id),
                        Subcategory::t(Subcategory::name).' as key',
                        DB::raw('COALESCE(' . SubcategoryLanguageString::tableName . '.' . SubcategoryLanguageString::value . ', ' . Subcategory::t(Subcategory::name) . ') as name'),
                        Subcategory::t(Subcategory::ordering),
                        Subcategory::t(Subcategory::status),
                        Subcategory::t(Subcategory::categoryId),
                        Subcategory::t(Subcategory::addedDate),
                        Subcategory::t(Subcategory::addedUserId),
                        Subcategory::t(Subcategory::updatedUserId),
                        Category::t(Category::name) . ' as cat_name')

                    ->when($id, function ($q) use($id) {
                        $q->where(Subcategory::t(Subcategory::id), $id);
                    })
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })
                    ->when($conds, function ($q, $conds) {
                        $q->where($conds);
                    })
                    ->groupBy(Subcategory::t(Subcategory::id))
                    ->when(empty($id) , function ($q) {
                        $q->orderBy(Subcategory::t(Subcategory::addedDate), 'desc')
                        ->orderBy(Subcategory::status, 'desc')
                        ->orderBy(Subcategory::name, 'asc');
                    })
                    ->first();
            });
    }

    public function getAll($relation = null, $status = null, $languageId = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $langConds = $this->prepareLanguageData();
        $activeLanguage = $this->languageService->get(null, $langConds);
        $langId = $languageId ?? $activeLanguage->id;

        $param = [$relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage, $languageId];
        
        return PsCache::remember([SubcategoryCache::BASE], SubcategoryCache::GET_ALL_EXPIRY, $param,
            function() use($relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage, $sort, $langId) {

                $subCategories = Subcategory::leftjoin(SubcategoryLanguageString::tableName, function ($query) use ($langId) {
                    $query->on(Subcategory::t(Subcategory::name), '=', SubcategoryLanguageString::tableName.'.'.SubcategoryLanguageString::key)
                        ->where(SubcategoryLanguageString::tableName.'.'.SubcategoryLanguageString::languageId, $langId);
                })
                    ->join(Category::tableName, Category::t(Category::id), '=', Subcategory::t(Subcategory::categoryId))
                    ->select(Subcategory::t(Subcategory::id),
                        Subcategory::t(Subcategory::name).' as key',
                        DB::raw('COALESCE(' . SubcategoryLanguageString::tableName . '.' . SubcategoryLanguageString::value . ', ' . Subcategory::t(Subcategory::name) . ') as name'),
                        Subcategory::t(Subcategory::ordering),
                        Subcategory::t(Subcategory::status),
                        Subcategory::t(Subcategory::categoryId),
                        Subcategory::t(Subcategory::addedDate),
                        Subcategory::t(Subcategory::addedUserId),
                        Subcategory::t(Subcategory::updatedUserId),
                        Category::t(Category::name) . ' as cat_name')
                        
                    ->groupBy(Subcategory::t(Subcategory::id))
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })
                    ->when($status, function ($q, $status) {
                        $q->where(Subcategory::t(SubCategory::status), $status);
                    })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })
                    ->when(empty($sort), function ($query, $soft) {
                        $query->orderBy(Subcategory::tableName.'.'.Subcategory::addedDate, 'desc')
                        ->orderBy(Subcategory::t(SubCategory::status), 'desc')
                        ->orderBy(Subcategory::name, 'asc');                        
                    });

                if ($pagPerPage) {
                    $subCategories = $subCategories->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    $subCategories = $subCategories->get();
                } else {
                    $subCategories = $subCategories->get();
                }

                return $subCategories;

            });
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            $subcategory = $this->updateSubcategory($id, $status);

            PsCache::clear(SubcategoryCache::BASE);

            return $subcategory;

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function importCSVFile($subcategoryData)
    {
        try {
            $import = new SubcategoryImport();
            $import->import($subcategoryData);

            PsCache::clear(SubcategoryCache::BASE);
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
    private function prepareSaveImageData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => Constants::subcategoryCoverImgType,
        ];
    }

    private function prepareSaveIconData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => Constants::subcategoryIconImgType,
        ];
    }

    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveSubcategory($subcategoryData)
    {
        $subcategory = new Subcategory();
        $subcategory->fill($subcategoryData);
        $subcategory->name = $this->coreKeyCounterService->generate(Constants::subCategoryLanguage);
        $subcategory->added_user_id = Auth::user()->id;
        $subcategory->save();

        return $subcategory;
    }

    private function updateSubcategory($id, $subcategoryData)
    {
        $subcategory = $this->get($id);
        $subcategory->updated_user_id = Auth::user()->id;
        $subcategory->update($subcategoryData);

        return $subcategory;
    }

    private function generateSubCategoryLanguageString($languageString, $subCategoryNameKey, $subCategoryId)
    {
        if($languageString != null && is_array($languageString)) {
            $nameForm = new \stdClass();
            foreach ($languageString as $key => $value) {
                $nameForm->$key = $value;
            }

            $this->subCategoryLanguageService->save($subCategoryNameKey, $subCategoryId, $nameForm->values ?? []);
        }

    }

    private function delectSubCategoryLanguages($subCategoryId)
    {
        $categoryLanguages = SubCategoryLanguageString::where(SubCategoryLanguageString::subcategoryId, $subCategoryId)->get();
        if ($categoryLanguages) {
            foreach ($categoryLanguages as $language) {
                $language->delete();
            }
        }
    }

    private function deleteSubcategory($id)
    {
        $subcategory = $this->get($id);
        $name = $subcategory->name;
        $subcategory->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Subcategory::tableName.'.'.Subcategory::name, 'like', '%'.$search.'%');
            });
        }

        if (isset($conds['category_id']) && $conds['category_id']) {
            $category_filter = $conds['category_id'];
            $query->whereHas('category', function ($q) use ($category_filter) {
                $q->where(Subcategory::tableName.'.'.Subcategory::categoryId, $category_filter);
            });
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(Subcategory::tableName.'.'.Subcategory::id, $conds['order_type']);
            } elseif ($conds['order_by'] == 'category_id@@name') {

                $query->orderBy('cat_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }

        return $query;
    }

    private function prepareLanguageData()
    {
        return ['symbol' => Session::get('applocale') ?? 'en'];
    }
}
