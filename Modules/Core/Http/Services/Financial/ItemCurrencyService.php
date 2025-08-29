<?php

namespace Modules\Core\Http\Services\Financial;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Imports\CategoryImport;
use App\Http\Contracts\Financial\ItemCurrencyServiceInterface;

class ItemCurrencyService extends PsService implements ItemCurrencyServiceInterface
{
    public function __construct()
    {}

    public function save($itemCurrencyData)
    {
        DB::beginTransaction();
        try {
            $this->saveItemCurrency($itemCurrencyData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $itemCurrencyData)
    {
        DB::beginTransaction();
        try {
           
            $this->updateItemCurrency($id, $itemCurrencyData);

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
        $currencies = ItemCurrency::when($status, function ($q, $status) {
            $q->where(ItemCurrency::status, $status);
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
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(ItemCurrency::isDefault, 'desc')
                    ->orderBy("added_date", 'desc')
                    ->orderBy(ItemCurrency::status, 'desc')
                    ->orderBy(ItemCurrency::currencyShortForm, 'asc');
            });
        if ($pagPerPage) {
            $currencies = $currencies->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $currencies = $currencies->get();
        } else {
            $currencies = $currencies->get();
        }
        return $currencies;
    }

    public function get($id = null, $conds = null)
    {
        $currency = ItemCurrency::when($id, function ($q, $id) {
            $q->where(ItemCurrency::id, $id);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->first();
        return $currency;
    }

    public function delete($id)
    {
        try {
            $currency = $this->get($id);
            if ($currency->is_default == 1) {
                return[
                    'msg' => 'The ' . $currency->currency_short_form . ' row cannot be deleted because it is default currency.',
                    'flag' => Constants::danger
                ];
            }
            $name = $this->deleteItemCurrency($id);

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

            return $this->updateItemCurrency($id, $status);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function defaultChange($id, $status)
    {
        try {
            $status = $this->prepareUpdateIsDefaultData($status);
            
            $this->undefaultAllCurrencies();
            
            $this->updateItemCurrency($id, $status);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function import($file)
    {
        try {
            Excel::import(new CategoryImport(), $file->store('temp'));
            return [
                'msg' => "Category is imported successfully",
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
    private function prepareUpdateIsDefaultData($status)
    {
        return ['is_default' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveItemCurrency($itemCurrencyData)
    {
        if ($itemCurrencyData['is_default'] == Constants::default) {
            $this->undefaultAllCurrencies();
        }

        $itemCurrency = new ItemCurrency();
        $itemCurrency->fill($itemCurrencyData);
        $itemCurrency->added_user_id = Auth::user()->id;
        $itemCurrency->save();
    }

    private function updateItemCurrency($id, $itemCurrencyData)
    {
        $itemCurrency = $this->get($id);
        $itemCurrency->updated_user_id = Auth::user()->id;
        $itemCurrency->update($itemCurrencyData);
    }

    private function deleteItemCurrency($id)
    {
        $itemCurreny = $this->get($id);
        $name = $itemCurreny->currency_short_form;
        $itemCurreny->delete();

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
                $query->where(ItemCurrency::tableName . '.' . ItemCurrency::currencySymbol, 'like', '%' . $search . '%')
                    ->orWhere(ItemCurrency::tableName . '.' . ItemCurrency::currencyShortForm, 'like', '%' . $search . '%');
            });
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(ItemCurrency::tableName . '.' . ItemCurrency::addedUserId, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(ItemCurrency::tableName . '.' . ItemCurrency::id, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    private function undefaultAllCurrencies()
    {
        $itemCurrencies = $this->getAll();

        foreach ($itemCurrencies as $currency) {
            $currency->update(['is_default' => Constants::unDefault]);
        }
    }
}

