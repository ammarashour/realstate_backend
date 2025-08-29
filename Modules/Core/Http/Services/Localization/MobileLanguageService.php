<?php

namespace Modules\Core\Http\Services\Localization;

use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Localization\MobileLanguage;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;

class MobileLanguageService extends PsService implements MobileLanguageServiceInterface
{
    // protected $mobileLanguageStringService;
    public function __construct(
        protected MobileLanguageStringServiceInterface $mobileLanguageStringService
    )
    {
        // $this->mobileLanguageStringService = function () {
        //     return app()->make(MobileLanguageStringServiceInterface::class);
        // };
    }

    public function save($mbLangData)
    {
        
        DB::beginTransaction();

        try {
            $mobileLanguage = $this->saveMbLanguage($mbLangData);

            $activeLanguage = $this->get(null, [MobileLanguage::status => 1]);

            $this->mobileLanguageStringService->copyAll($activeLanguage, $mobileLanguage);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $mobileLanguage;
    }

    public function update($id, $mbLangData)
    {
        DB::beginTransaction();

        try {
            $this->updateMbLanguage($id, $mbLangData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getAll($enable = null, $limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null)
    {
        $mobileLanguages = MobileLanguage::when($enable, function ($query, $enable) {
            $query->where(MobileLanguage::enable, $enable);
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
            $mobileLanguages = $mobileLanguages->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $mobileLanguages = $mobileLanguages->get();
        } else {
            $mobileLanguages = $mobileLanguages->get();
        }
        return $mobileLanguages;
    }

    public function get($id = null, $conds = null)
    {
        $language = MobileLanguage::when($id, function ($query, $id) {
            $query->where(MobileLanguage::id, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $language;
    }

    public function delete($id)
    {
        try {
            $language = $this->deleteMbLanguage($id);

            $this->mobileLanguageStringService->deleteByLanguageId($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $language->name]),
                'flag' => Constants::success,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $language = $this->get($id);

            $this->unPublishAllLanguages();

            $language->status = $status;
            $language->save();

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function enableDisable($id, $status)
    {
        try {
            $mobileLanguage = $this->get($id);
            $mobileLanguage->enable = $status;
            $mobileLanguage->updated_user_id = Auth::user()->id;
            $mobileLanguage->update();
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------


    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveMbLanguage($mbLangData)
    {
        $mobileLanguage = new MobileLanguage();
        $mobileLanguage->fill($mbLangData);
        $mobileLanguage->language_code = $mbLangData['languageCode'];
        $mobileLanguage->country_code = $mbLangData['countryCode'];
        $mobileLanguage->code = Carbon::now()->getPreciseTimestamp(3);
        $mobileLanguage->added_user_id = Auth::user()->id;
        $mobileLanguage->save();

        return $mobileLanguage;
    }

    // private function saveMbLangStrings($mobileLanguage, $activeLanguage)
    // {
    //     $languageStrings = $this->getMobileLanguageStringService()->getAll($activeLanguage->id);
    //     foreach ($languageStrings as $languageString) {
    //         $languageStringData[] = [
    //             'key' => $languageString->key,
    //             'value' => $languageString->value,
    //             'mobile_language_id' => $mobileLanguage->id,
    //             'added_user_id' => Auth::id(),
    //         ];
    //     }
    //     MobileLanguageString::insert($languageStringData);
    // }

    private function updateMbLanguage($id, $mbLangData)
    {
        $mobileLanguage = $this->get($id);
        $mobileLanguage->code = Carbon::now()->getPreciseTimestamp(3);
        $mobileLanguage->updated_user_id = Auth::user()->id;
        $mobileLanguage->language_code = $mbLangData['languageCode'];
        $mobileLanguage->country_code = $mbLangData['countryCode'];
        $mobileLanguage->update($mbLangData);
    }

    private function deleteMbLanguage($id)
    {
        $language = $this->get($id);    
        $language->delete();

        return $language;
    }

    private function searching($query, $conds)
    {
        // search by keyword

        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }

        // Filter with id
        if (isset($conds['id']) && $conds['id']) {
            $search = $conds['id'];
            $query->where(function ($query) use ($search) {
                $query->where(MobileLanguage::id, '=', $search);
            });
        }

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(MobileLanguage::tableName . '.' . MobileLanguage::symbol, 'like', '%' . $search . '%')
                    ->orWhere(MobileLanguage::tableName . '.' . MobileLanguage::name, 'like', '%' . $search . '%')
                    ->orWhere(MobileLanguage::tableName . '.' . MobileLanguage::languageCode, 'like', '%' . $search . '%')
                    ->orWhere(MobileLanguage::tableName . '.' . MobileLanguage::countryCode, 'like', '%' . $search . '%');
            });
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(MobileLanguage::tableName . '.id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(MobileLanguage::tableName . '.status', 'desc')->orderBy(MobileLanguage::tableName . '.enable', 'desc')->orderBy(MobileLanguage::tableName . '.name', 'asc');
        }

        return $query;
    }

    private function unPublishAllLanguages()
    {
        $languages = $this->getAll();
        foreach ($languages as $language) {
            $language->update(['status' => Constants::unPublish]);
        }
    }

}
