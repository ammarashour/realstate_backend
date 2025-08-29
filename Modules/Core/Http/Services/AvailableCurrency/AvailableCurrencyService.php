<?php

namespace Modules\Core\Http\Services\AvailableCurrency;

use App\Config\Cache\AppInfoCache;
use App\Http\Contracts\AvailableCurrency\AvailableCurrencyServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class AvailableCurrencyService extends PsService implements AvailableCurrencyServiceInterface
{
    public function __construct(
        protected CoreFieldFilterSettingService $coreFieldFilterSettingService) {}

    public function save($availableCurrencyData)
    {
        DB::beginTransaction();

        try {

            // save in available currency table
            $this->saveAvailableCurrency($availableCurrencyData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $availableCurrencyData)
    {
        DB::beginTransaction();

        try {

            // update in location_cities table
            $this->updateAvailableCurrency($id, $availableCurrencyData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteAvailableCurrency($id);

            PsCache::clear(AppInfoCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $relation = null, $conds = null)
    {
        return AvailableCurrency::when($id, function($q, $id) {
            $q->where(AvailableCurrency::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->first();
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {

        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $available_currencies = AvailableCurrency::when($status, function ($q, $status) {
            $q->where(AvailableCurrency::status, $status);

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
            ->orderBy(AvailableCurrency::isDefault, 'desc')
            ->latest();
        if ($pagPerPage) {
            return $available_currencies->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            return $available_currencies->get();
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            PsCache::clear(AppInfoCache::BASE);

            return $this->updateAvailableCurrency($id, $status);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function defaultChange($id)
    {
        try {
            $conds = $this->prepareDefaultDataCond(Constants::publish);
            $defaultAvailableCurrency = $this->get(null, null, $conds);

            $isDefault = $this->prepareDefaultDataCond(Constants::unPublish);
            $this->updateAvailableCurrency($defaultAvailableCurrency->id, $isDefault);

            $isDefault = $this->prepareDefaultData(Constants::publish, Constants::publish);
            $availableCurrency = $this->updateAvailableCurrency($id, $isDefault);

            PsCache::clear(AppInfoCache::BASE);

            return [
                'msg' => 'The '.$availableCurrency->currency_short_form.' row has been changed to default status successfully.',
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

    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    private function prepareDefaultDataCond($isDefault)
    {
        return ['is_default' => $isDefault];
    }

    private function prepareDefaultData($isDefault, $status)
    {
        return [
            'is_default' => $isDefault,
            'status' => $status
        ];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function saveAvailableCurrency($availableCurrencyData)
    {
        $available_currencies = new AvailableCurrency();
        $available_currencies->fill($availableCurrencyData);
        $available_currencies->added_user_id = Auth::user()->id;
        $available_currencies->save();

        return $available_currencies;
    }

    private function updateAvailableCurrency($id, $availableCurrencyData)
    {
        $available_currencies = $this->get($id);
        $available_currencies->updated_user_id = Auth::user()->id;
        $available_currencies->update($availableCurrencyData);

        return $available_currencies;
    }

    private function deleteAvailableCurrency($id)
    {
        $available_currencies = $this->get($id);
        $name = $available_currencies->name;
        $available_currencies->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(AvailableCurrency::tableName.'.'.AvailableCurrency::currencySymbol, 'like', '%'.$search.'%')
                    ->orWhere(AvailableCurrency::tableName.'.'.AvailableCurrency::currencyShortForm, 'like', '%'.$search.'%');
            });
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(AvailableCurrency::tableName.'.'.AvailableCurrency::addedUserId, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy('available_currencies.id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }
}
