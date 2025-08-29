<?php

namespace Modules\Core\Http\Services\Financial;

use App\Config\Cache\AppInfoCache;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Http\Facades\PsCache;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;

class PaymentInfoService extends PsService implements PaymentInfoServiceInterface
{
    public function __construct() {}

    public function save($paymentInfoData)
    {
        DB::beginTransaction();

        try {

            $paymentInfo = $this->savePaymentInfo($paymentInfoData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            return $paymentInfo;
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $paymentInfoData)
    {

        DB::beginTransaction();

        try {

            $paymentInfoData = $this->updatePaymentInfo($id, $paymentInfoData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            return $paymentInfoData;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id = null, $conds = null)
    {
        try {
            $this->deletePaymentInfo($id, $conds);

            PsCache::clear(AppInfoCache::BASE);

            return [
                'msg' => __('core__be_delete_success'),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relations = null)
    {

        return PaymentInfo::when($id, function ($query, $id) {
            $query->where(PaymentInfo::id, $id);
        })
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
    }

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $attribute = null, $serviceFrom = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $paymentInfos = PaymentInfo::select(PaymentInfo::tableName . '.*', PaymentAttribute::tableName . '.' . PaymentAttribute::attributeValue . ' as payment_status')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'title' || $sort == 'description') {
                    $q->leftjoin(CoreKey::tableName, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId, '=', CoreKey::tableName . '.' . CoreKey::coreKeysId);
                    $q->select(CoreKey::tableName . '.' . CoreKey::name . ' as payment_title', CoreKey::tableName . '.' . CoreKey::description . ' as payment_description', PaymentInfo::tableName . '.*');
                }
                if ($sort == 'in_app_purchase_prd_id') {
                    $q->leftjoin(CoreKey::tableName, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId, '=', CoreKey::tableName . '.' . CoreKey::coreKeysId);
                    $q->select(CoreKey::tableName . '.' . CoreKey::name . ' as iap_product_id', PaymentInfo::tableName . '.*');
                }
            })

            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($attribute, function ($query, $attribute) {
                $sql = $this->getSqlForCustomField($attribute);
                $query->selectRaw($sql);
            })
            ->leftJoin(PaymentAttribute::tableName, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId, '=', PaymentAttribute::tableName . '.' . PaymentAttribute::coreKeysId)
            ->groupBy(PaymentInfo::tableName . '.' . PaymentInfo::id)
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query) use ($serviceFrom) {
                if ($serviceFrom == 'PromotionIAP') {
                    $query->orderBy('added_date', 'desc');
                } else {
                    $query->orderBy('added_date', 'desc');
                }
            });

        if ($pagPerPage) {
            $paymentInfos = $paymentInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $paymentInfos = $paymentInfos->get();
        }

        return $paymentInfos;
    }
    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function savePaymentInfo($paymentInfoData)
    {
        $paymentInfo = new PaymentInfo();
        $paymentInfo->fill($paymentInfoData);
        $paymentInfo->added_user_id = Auth::user()->id;
        $paymentInfo->save();

        return $paymentInfo;
    }

    private function updatePaymentInfo($id, $paymentInfoData)
    {
        $paymentInfo = $this->get($id);
        $paymentInfo->added_user_id = Auth::user()->id;
        $paymentInfo->update($paymentInfoData);

        return $paymentInfo;
    }

    private function deletePaymentInfo($id, $conds)
    {
        $paymentInfo = $this->get($id, $conds);
        $paymentInfo->delete();
    }

    private function getSqlForCustomField($attributes)
    {
        $sql = '';
        foreach ($attributes as $attribute) {
            $sql .= "max(case when psx_payment_attributes.attribute_key = '$attribute' then ";
            if ($attribute == 'price' || $attribute == 'count' || $attribute == 'days') {

                $sql .= "psx_payment_attributes.attribute_value end) as '$attribute' ,";
            } else {

                $sql .= "psx_payment_attributes.attribute_value end) as '$attribute' ,";
            }
        }
        if ($sql) {
            $sql = rtrim($sql, ',');
        }

        return $sql;
    }

    private function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            if ($search) {

                $query->join(CoreKey::tableName, CoreKey::tableName . '.' . CoreKey::coreKeysId, '=', PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId);
            }

            $query->where(function ($query) use ($search) {
                $query->where(PaymentInfo::value, 'like', '%' . $search . '%');
                $query->orWhere(CoreKey::tableName . '.' . CoreKey::name, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['payment_id']) && $conds['payment_id']) {
            $query->where(PaymentInfo::tableName . '.' . PaymentInfo::paymentId, $conds['payment_id']);
        }

        if (isset($conds['core_keys_id']) && $conds['core_keys_id']) {
            $query->where(PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId, $conds['core_keys_id']);
        }

        if (isset($conds['value']) && $conds['value']) {
            $query->where('value', $conds['value']);
        }

        if (isset($conds['type']) && $conds['type']) {
            $query->having('type', $conds['type']);
        }

        if (isset($conds['day']) && $conds['day']) {
            $query->having('day', $conds['day']);
        }

        if (isset($conds['currency_id']) && $conds['currency_id']) {
            $query->having('currency_id', $conds['currency_id']);
        }

        if (isset($conds['post_count']) && $conds['post_count']) {
            $query->having('post_count', $conds['post_count']);
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->having('status', $conds['status']);
        }

        if (isset($conds['count']) && $conds['count']) {
            $query->having('count', $conds['count']);
        }

        if (isset($conds['shop_id']) && $conds['shop_id']) {
            $query->where('shop_id', $conds['shop_id']);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where('added_date', $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where('added_user_id', $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'title') {
                $query->orderBy('payment_title', $conds['order_type']);
            } elseif ($conds['order_by'] == 'description') {
                $query->orderBy('payment_description', $conds['order_type']);
            } elseif ($conds['order_by'] == 'status') {
                $query->orderBy('payment_status', $conds['order_type']);
            } elseif ($conds['order_by'] == 'in_app_purchase_prd_id') {
                $query->orderBy('iap_product_id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'type') {
                $query->orderBy('type', $conds['order_type']);
            } elseif ($conds['order_by'] == 'days') {
                // dd("here");
                // $query->orderBy('days', $conds['order_type']);
                // $query->orderByRaw('section::int",' desc');
                $query->orderBy('days', $conds['order_type']);
            } elseif ($conds['order_by'] == 'count') {

                $query->orderBy('count', $conds['order_type']);
            } elseif ($conds['order_by'] == 'price') {
                $query->orderBy('price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'currency_id') {
                $query->orderBy('currency_id', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'sale_price') {
                $query->orderBy('sale_price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'duration') {
                $query->orderBy('duration', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'discount_price') {
                $query->orderBy('discount_price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'is_most_popular_plan') {
                $query->orderBy('is_most_popular_plan', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } else {
                $query->orderBy('psx_payment_infos.' . $conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }
}
