<?php

namespace Modules\Core\Http\Services\Financial;

use App\Config\Cache\PaymentInfoCache;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Contracts\Financial\CoreKeyPaymentRelationServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Transformers\Backend\NoModel\Fianacial\PromotionInAppPurchaseWithKeyResource;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use App\Http\Contracts\Financial\PromotionInAppPurchaseSettingServiceInterface;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class PromotionInAppPurchaseSettingService extends PsService implements PromotionInAppPurchaseSettingServiceInterface
{
    public function __construct(
        protected CoreKeyPaymentRelationServiceInterface $coreKeyPaymentRelationService,
        protected PaymentService $paymentService,
        protected CoreKeyServiceInterface $coreKeyService,
        protected PaymentSettingService $paymentSettingService,
        protected PaymentAttributeServiceInterface $paymentAttributeService
    ) {}

    public function save($PromotionIAPData)
    {
        DB::beginTransaction();

        try {
            $coreKeysId = $this->saveCoreKey($PromotionIAPData);

            $this->saveCoreKeyPaymentRelation($coreKeysId);

            $this->savePaymentInfo($coreKeysId, $PromotionIAPData);

            $this->savePaymentAttrTypeCol($coreKeysId, $PromotionIAPData);

            $this->savePaymentAttrDayCol($coreKeysId, $PromotionIAPData);

            $this->savePaymentAttrStatusCol($coreKeysId, $PromotionIAPData);

            DB::commit();

            PsCache::clear(PaymentInfoCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $PromotionIAPData)
    {

        DB::beginTransaction();

        try {

            $this->updateCoreKey($PromotionIAPData);

            $this->updatePaymentInfo($id, $PromotionIAPData);

            $this->updatePaymentAttrTypeCol($PromotionIAPData);

            $this->updatePaymentAttrDayCol($PromotionIAPData);

            $this->updatePaymentAttrStatusCol($PromotionIAPData->core_keys_id, $PromotionIAPData->toArray());

            DB::commit();
            PsCache::clear(PaymentInfoCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $paymentInfos = PaymentInfo::when($relations, function ($query, $relations) {
            $query->with($relations);
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
            $paymentInfos = $paymentInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $paymentInfos = $paymentInfos->get();
        }

        return $paymentInfos;
    }

    public function get($id, $relations = null, $conds = null)
    {
        $paymentInfo = PaymentInfo::where(PaymentInfo::id, $id)
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $paymentInfo;
    }

    public function delete($id)
    {
        $paymentInfo = PaymentInfo::find($id);
        $coreKey = CoreKey::where(CoreKey::coreKeysId, $paymentInfo->core_keys_id)->first();
        $coreKeyPaymentRelation = CoreKeyPaymentRelation::where(CoreKey::coreKeysId, $paymentInfo->core_keys_id)->first();
        $paymentAttributes = PaymentAttribute::where(PaymentAttribute::coreKeysId, $paymentInfo->core_keys_id)->get();
        $name = $coreKey->name;

        $paymentInfo->delete();
        $coreKey->delete();
        $coreKeyPaymentRelation->delete();


        PaymentAttribute::destroy($paymentAttributes->pluck('id'));

        $dataArr = [
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            'flag' => Constants::danger,
        ];

        PsCache::clear(PaymentInfoCache::BASE);

        return $dataArr;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStatusData($status);
            $this->updatePaymentAttrStatusCol($id, $status);

            PsCache::clear(PaymentInfoCache::BASE);
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

    private function prepareUpdateStatusData($status)
    {
        return ['status' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCoreKey($PromotionIAPData)
    {
        $coreKey = new \stdClass();
        $coreKey->name = $PromotionIAPData['in_app_purchase_prd_id'];
        $coreKey->description = $PromotionIAPData['in_app_purchase_prd_id'];
        $core_key = $this->coreKeyService->save($coreKey, Constants::payment);
        return $core_key->core_keys_id;
    }
    private function saveCoreKeyPaymentRelation($coreKeysId)
    {
        // save core_keys_id and payment_id to core_key_payment_relations table
        $coreKeyPaymentRelation = new \stdClass();
        $coreKeyPaymentRelation->core_keys_id = $coreKeysId;
        $coreKeyPaymentRelation->payment_id = Constants::promotionInAppPurchasePaymentId;
        $this->coreKeyPaymentRelationService->save((array)$coreKeyPaymentRelation);
    }

    private function savePaymentInfo($coreKeysId, $PromotionIAPData)
    {

        // save core_keys_id, payment_id and value to payment_infos table
        $paymentInfo = new PaymentInfo();
        $paymentInfo->core_keys_id = $coreKeysId;
        $paymentInfo->payment_id = Constants::promotionInAppPurchasePaymentId;
        // save description as value to payment_infos
        $paymentInfo->value = $PromotionIAPData['description'];
        $paymentInfo->added_user_id = isset($PromotionIAPData['added_user_id']) && !empty($PromotionIAPData['added_user_id']) ? $PromotionIAPData['added_user_id'] : Auth::user()->id;
        $paymentInfo->save();
    }

    private function savePaymentAttrTypeCol($coreKeysId, $PromotionIAPData)
    {

        // save payment_attributes table For Type Col
        $paymentAttributeTypeData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $coreKeysId,
            "attribute_key" => Constants::pmtAttrPromoteIapTypeCol,
            "attribute_value" => $PromotionIAPData['type']
        ];
        $this->paymentAttributeService->save($paymentAttributeTypeData);
    }

    private function savePaymentAttrDayCol($coreKeysId, $PromotionIAPData)
    {

        // save payment_attributes table For Day Col
        $paymentAttributeDayData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $coreKeysId,
            "attribute_key" => Constants::pmtAttrPromoteIapDayCol,
            "attribute_value" => $PromotionIAPData['day']
        ];
        $this->paymentAttributeService->save($paymentAttributeDayData);
    }

    private function savePaymentAttrStatusCol($coreKeysId, $PromotionIAPData)
    {
        // save payment_attributes table For Status Col
        $paymentAttributeStatusData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $coreKeysId,
            "attribute_key" => Constants::pmtAttrPromoteIapStatusCol,
            "attribute_value" => $PromotionIAPData['status']
        ];
        $this->paymentAttributeService->save($paymentAttributeStatusData);
    }

    private function updateCoreKey($PromotionIAPData)
    {
        // update core key table
        $coreKey = new \stdClass();
        $coreKey->name = $PromotionIAPData['in_app_purchase_prd_id'];
        $coreKey->description = $PromotionIAPData['in_app_purchase_prd_id'];
        CoreKey::where('core_keys_id', $PromotionIAPData['core_keys_id'])->first()->id;
        $this->coreKeyService->update($PromotionIAPData['core_keys_id'], $coreKey);
    }

    private function updatePaymentInfo($id, $PromotionIAPData)
    {
        // update payment info table
        $paymentInfo = $this->get($id);
        $paymentInfo->core_keys_id = $PromotionIAPData['core_keys_id'];
        $paymentInfo->payment_id = Constants::promotionInAppPurchasePaymentId;
        if (isset($PromotionIAPData['description']) && !empty($PromotionIAPData['description']))
            $paymentInfo->value = $PromotionIAPData['description'];
        if (isset($PromotionIAPData['updated_user_id']) && !empty($PromotionIAPData['updated_user_id']))
            $paymentInfo['updated_user_id'] = $PromotionIAPData['updated_user_id'];
        else
            $paymentInfo->updated_user_id = Auth::user()->id;
        $paymentInfo->update();
    }

    private function updatePaymentAttrTypeCol($PromotionIAPData)
    {
        // update payment attributes table For Type Col
        $conds['attribute_key'] = Constants::pmtAttrPromoteIapTypeCol;
        $conds['core_keys_id'] = $PromotionIAPData['core_keys_id'];
        $paymentAttributeType = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeTypeData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $PromotionIAPData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrPromoteIapTypeCol,
            "attribute_value" => $PromotionIAPData['type']
        ];
        if ($paymentAttributeType) {
            $this->paymentAttributeService->update($paymentAttributeType->id, $paymentAttributeTypeData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeTypeData);
        }
    }

    private function updatePaymentAttrDayCol($PromotionIAPData)
    {
        // update payment attributes table For Day Col
        $conds['attribute_key'] = Constants::pmtAttrPromoteIapDayCol;
        $conds['core_keys_id'] = $PromotionIAPData['core_keys_id'];
        $paymentAttributeDay = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeDayData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $PromotionIAPData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrPromoteIapDayCol,
            "attribute_value" => $PromotionIAPData['day']
        ];
        if ($paymentAttributeDay) {
            $this->paymentAttributeService->update($paymentAttributeDay->id, $paymentAttributeDayData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeDayData);
        }
    }

    private function updatePaymentAttrStatusCol($id, $PromotionIAPData)
    {
        // update payment attributes table For Status Col
        $conds['attribute_key'] = Constants::pmtAttrPromoteIapStatusCol;
        $conds['core_keys_id'] = $id;
        $paymentAttributeStatus = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeStatusData = [
            "payment_id" => Constants::promotionInAppPurchasePaymentId,
            "core_keys_id" => $id,
            "attribute_key" => Constants::pmtAttrPromoteIapStatusCol,
            "attribute_value" => $PromotionIAPData['status']
        ];
        if ($paymentAttributeStatus) {
            $this->paymentAttributeService->update($paymentAttributeStatus->id, $paymentAttributeStatusData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeStatusData);
        }
    }

    private function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(PaymentInfo::value, 'like', '%' . $search . '%');
            });
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
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }
}
