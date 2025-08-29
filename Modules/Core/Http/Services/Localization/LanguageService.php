<?php

namespace Modules\Core\Http\Services\Localization;

use App\Config\Cache\LocalizationCache;
use App\Helpers\PsLanguageJsonHelper;
use App\Http\Contracts\Localization\FeLanguageStringServiceInterface;
use App\Models\User;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Localization\Language;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Localization\BeLanguageStringServiceInterface;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class LanguageService extends PsService implements LanguageServiceInterface
{

    public function __construct(
        protected VendorLanguageStringServiceInterface $vendorLanguageStringService,
        protected FeLanguageStringServiceInterface $feLanguageStringService,
        protected BeLanguageStringServiceInterface $beLanguageStringService
    ) {}

    public function save($beLangData)
    {

        DB::beginTransaction();
        try {

            $language = $this->saveLanguage($beLangData);

            $activeLanguage = $this->get(null, [Language::status => 1]);

            $this->beLanguageStringService->copyAll($activeLanguage, $language);

            $this->feLanguageStringService->copyAll($activeLanguage, $language);

            $this->vendorLanguageStringService->copyAll($activeLanguage, $language);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $beLangData)
    {
        DB::beginTransaction();
        try {

            $this->updateLanguage($id, $beLangData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function get($id = null, $conds = null)
    {
        $language = Language::when($id, function ($query, $id) {
            $query->where(Language::id, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $language;
    }

    public function getAll($relations = null, $pagPerPage = null, $conds = null)
    {
        $languages = Language::when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
            if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                $q->leftJoin(User::tableName, User::name . '.' . User::id, '=', Language::tableName . '.' . $conds['order_by']);
                $q->select(User::tableName . '.' . User::name . ' as owner', Language::tableName . '.*');
            }
        })->when($conds, function ($query, $conds) {
            $query = $this->searching($query, $conds);
        })->when($relations, function ($q, $relations) {
            $q->with($relations);
        })->latest();

        if ($pagPerPage) {
            $languages = $languages->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $languages = $languages->get();
        }
        return $languages;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // Delete Language
            $language = $this->deleteLanguage($id);

            // Delete Backend Language Strings
            $this->beLanguageStringService->deleteByLanguageId($id);

            // Delete Frontend Language Strings
            $this->feLanguageStringService->deleteByLanguageId($id);

            // Delete Vendor Language Strings
            $this->vendorLanguageStringService->deleteByLanguageId($id);

            // Delete Json File
            PsLanguageJsonHelper::deleteJsonFile($language->symbol);

            PsCache::clear(LocalizationCache::BASE);

            DB::commit();

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $language->name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $language = $this->get($id);

            $this->unPublishAllLanguages();

            $language->status = $status;
            $language->save();

            DB::commit();
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

    // N.A

    //
    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveLanguage($beLangData)
    {
        $language = new Language();
        $language->fill($beLangData);
        $language->added_user_id = Auth::user()->id;
        $language->save();
        return $language;
    }

    private function updateLanguage($id, $beLangData)
    {
        $language = $this->get($id);
        $language->updated_user_id = Auth::user()->id;
        $language->update($beLangData);
    }

    private function deleteLanguage($id)
    {
        $language = $this->get($id);
        $language->delete();

        return $language;
    }


    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Language::name, 'like', '%' . $search . '%');
            });
        }

        // is publish
        if (isset($conds['is_publish']) && $conds['is_publish']) {
            $query->where(function($query) use ($conds) {
                $query->where(Language::isPublish, $conds['is_publish']);
            });
        }

        // Filter with id
        if (isset($conds['id']) && $conds['id']) {
            $search = $conds['id'];
            $query->where(function ($query) use ($search) {
                $query->where(Language::id, '=', $search);
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
            $query->orderBy(Language::status, 'desc')->orderBy('name', 'asc');
        }
        return $query;
    }

    private function unPublishAllLanguages()
    {
        Language::where('status', '<>', Constants::unPublish)->update(['status' => Constants::unPublish]);
    }
}
