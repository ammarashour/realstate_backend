<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\PhoneCountryCode;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Item\Item;
use App\Http\Contracts\Configuration\PhoneCountryCodeServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class PhoneCountryCodeService extends PsService implements PhoneCountryCodeServiceInterface
{

    public function __construct() {}

    public function save($phoneCountryCodeData)
    {

        DB::beginTransaction();
        try {

            $this->savePhoneCountryCode($phoneCountryCodeData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $phoneCountryCodeData)
    {
        DB::beginTransaction();

        try {

            $this->updatePhoneCountryCode($id, $phoneCountryCodeData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }


    public function getAll($status = null, $isDefault = null, $limit = null, $offset = null, $noPagination = null,  $pagPerPage = null, $conds = null)
    {

        $sort = '';

        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $phoneCountryCodes = PhoneCountryCode::when($status, function ($q, $status) {
            $q->where(PhoneCountryCode::status, $status);
        })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query, $extra) {
                $query->orderBy('is_default', 'desc')
                    ->orderBy('status', 'desc')
                    ->orderBy('country_name', 'asc');
            });
        if ($pagPerPage) {
            $phoneCountryCodes = $phoneCountryCodes->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $phoneCountryCodes = $phoneCountryCodes->get();
        } else {
            $phoneCountryCodes = $phoneCountryCodes->get();
        }
        return $phoneCountryCodes;
    }

    public function get($id)
    {
        $phoneCountryCode = PhoneCountryCode::findOrFail($id);
        return $phoneCountryCode;
    }

    public function delete($id)
    {
        try {
            $phoneCountryCode = $this->get($id);

            if ($phoneCountryCode->is_default == 1) {
                return [

                    'msg' => 'The row cannot be deleted because it is default code.',
                    'flag' => Constants::danger
                ];
            }

            $name = $this->deletePhoneCountryCode($id);

            PsCache::clear(AppInfoCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            $this->updatePhoneCountryCode($id, $status);

            PsCache::clear(AppInfoCache::BASE);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function defaultChange($id, $status)
    {
        try {

            $this->unDefaultAll();

            $phoneCountryCode = $this->get($id);
            $phoneCountryCode->is_default = $status;
            $phoneCountryCode->update();

            PsCache::clear(AppInfoCache::BASE);
        } catch (\Throwable $e) {
            dd($e->getMessage());
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
    private function prepareUpdateIsDefaultData($status)
    {
        return ['is_default' => $status];
    }
    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function savePhoneCountryCode($phoneCountryCodeData)
    {
        $phoneCountryCode = new PhoneCountryCode();
        $phoneCountryCode->fill($phoneCountryCodeData);
        $phoneCountryCode->added_user_id = Auth::user()->id;
        $phoneCountryCode->save();
    }

    private function updatePhoneCountryCode($id, $phoneCountryCodeData)
    {
        $phoneCountryCode = $this->get($id);
        $phoneCountryCode->updated_user_id = Auth::user()->id;
        $phoneCountryCode->update($phoneCountryCodeData);
    }

    private function deletePhoneCountryCode($id)
    {
        $phoneCountryCode = $this->get($id);
        $name = $phoneCountryCode->country_name;
        $phoneCountryCode->delete();

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
                $query->where(PhoneCountryCode::tableName . '.' . PhoneCountryCode::countryName, 'like', '%' . $search . '%')
                    ->orWhere(PhoneCountryCode::tableName . '.' . PhoneCountryCode::countryCode, 'like', '%' . $search . '%');
            });
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(Item::tableName . '.' . Item::addedUserId, $conds['added_user_id']);
        }


        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy('phoneCountryCodes.id', $conds['order_type']);
            } else if ($conds['order_by'] == 'country_name') {
                $query->orderBy(PhoneCountryCode::tableName . '.' . PhoneCountryCode::countryName, $conds['order_type']);
            } else if ($conds['order_by'] == 'country_code') {
                $query->orderBy(PhoneCountryCode::tableName . '.' . PhoneCountryCode::countryCode, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    private function unDefaultAll()
    {
        $phoneCountryCodes = $this->getAll();

        foreach ($phoneCountryCodes as $phoneCountryCode) {
            $phoneCountryCode->is_default = Constants::unDefault;
            $phoneCountryCode->save(); // explicitly save each model
        }
    }
}
