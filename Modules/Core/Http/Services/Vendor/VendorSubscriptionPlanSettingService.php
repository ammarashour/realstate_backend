<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\PaymentInfoCache;
use App\Http\Contracts\Vendor\VendorSubscriptionPlanSettingServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Contracts\Financial\CoreKeyPaymentRelationServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class VendorSubscriptionPlanSettingService extends PsService implements VendorSubscriptionPlanSettingServiceInterface
{
    public function __construct(
        protected UserAccessApiTokenService $userAccessApiTokenService,
        protected CoreKeyPaymentRelationServiceInterface $coreKeyPaymentRelationService,
        protected PaymentService $paymentService,
        protected CoreKeyServiceInterface $coreKeyService,
        protected PaymentSettingService $paymentSettingService,
        protected PaymentAttributeServiceInterface $paymentAttributeService,
        protected AvailableCurrencyService $availableCurrencyService
    ) {}

    public function save($vendorSubscriptionPlanData)
    {
        DB::beginTransaction();
        try {
            $core_key = $this->saveCoreKey($vendorSubscriptionPlanData);

            $this->saveCoreKeyPaymentRelation($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentInfo($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrDurationCol($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrSalePriceCol($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrDiscountPriceCol($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrMostPopularCol($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrStatusCol($core_key, $vendorSubscriptionPlanData);

            $this->savePaymentAttrCurrencyCol($core_key, $vendorSubscriptionPlanData);

            PsCache::clear(AppInfoCache::BASE);
            PsCache::clear(PaymentInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $vendorSubscriptionPlanData)
    {

        DB::beginTransaction();

        try {

            $this->updateCoreKey($vendorSubscriptionPlanData);

            $this->updatePaymentInfo($id, $vendorSubscriptionPlanData);

            $this->updatePaymentAttrDurationCol($vendorSubscriptionPlanData);

            $this->updatePaymentAttrSalesPriceCol($vendorSubscriptionPlanData);

            $this->updatePaymentAttrDiscountPriceCol($vendorSubscriptionPlanData);

            $this->updatePaymentAttrStatusCol($id, $vendorSubscriptionPlanData);

            $this->updatePaymentAttrMostPopularCol($vendorSubscriptionPlanData);

            $this->updatePaymentAttrCurrencyCol($vendorSubscriptionPlanData);

            PsCache::clear(AppInfoCache::BASE);
            PsCache::clear(PaymentInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null)
    {
        $paymentInfos = PaymentInfo::when($relations, function ($query, $relations) {
            $query->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();

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
        $coreKeyPaymentRelation = CoreKeyPaymentRelation::where(CoreKeyPaymentRelation::coreKeysId, $paymentInfo->core_keys_id)->first();
        $paymentAttributes = PaymentAttribute::where(PaymentAttribute::coreKeysId, $paymentInfo->core_keys_id)->get();
        $name = $coreKey->name;

        $paymentInfo->delete();
        $coreKey->delete();
        $coreKeyPaymentRelation->delete();

        PaymentAttribute::destroy($paymentAttributes->pluck('id'));

        PsCache::clear(AppInfoCache::BASE);
        PsCache::clear(PaymentInfoCache::BASE);

        $dataArr = [
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            'flag' => constants::danger,
        ];

        return $dataArr;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStatusData($status);

            $this->updatePaymentAttrStatusCol($id, $status);

            PsCache::clear(AppInfoCache::BASE);
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

    private function saveCoreKey($vendorSubscriptionPlanData)
    {
        // save core key table
        $coreKey = [
            "name" => $vendorSubscriptionPlanData['in_app_purchase_prd_id'],
            "description" => $vendorSubscriptionPlanData['in_app_purchase_prd_id'],
        ];

        $core_key = $this->coreKeyService->save($coreKey, Constants::payment);

        return $core_key->core_keys_id;
    }

    private function saveCoreKeyPaymentRelation($core_key, $vendorSubscriptionPlanData)
    {
        // save core key payment relations table
        $coreKeyPaymentRelation = [
            "core_keys_id" => $core_key,
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
        ];
        $this->coreKeyPaymentRelationService->save($coreKeyPaymentRelation);
    }

    private function savePaymentInfo($core_key, $vendorSubscriptionPlanData)
    {
        // save payment info table
        $paymentInfo = new PaymentInfo();
        $paymentInfo->core_keys_id = $core_key;
        $paymentInfo->payment_id = Constants::vendorSubscriptionPlanPaymentId;
        if (isset($vendorSubscriptionPlanData['title']) && ! empty($vendorSubscriptionPlanData['title'])) {
            $paymentInfo->value = $vendorSubscriptionPlanData['title'];
        }
        if (isset($vendorSubscriptionPlanData['added_user_id']) && ! empty($vendorSubscriptionPlanData['added_user_id'])) {
            $paymentInfo->added_user_id = $vendorSubscriptionPlanData['added_user_id'];
        } else {
            $paymentInfo->added_user_id = Auth::user()->id;
        }
        $paymentInfo->save();
    }

    private function savePaymentAttrDurationCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Duration Col
        $paymentAttributeDuration = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpDurationCol,
            "attribute_value" => $vendorSubscriptionPlanData['duration']
        ];
        $this->paymentAttributeService->save($paymentAttributeDuration);
    }

    private function savePaymentAttrSalePriceCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Sale Price Col
        $paymentAttributeSalePrice = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpSalePriceCol,
            "attribute_value" => $vendorSubscriptionPlanData['sale_price']
        ];
        $this->paymentAttributeService->save($paymentAttributeSalePrice);
    }

    private function savePaymentAttrDiscountPriceCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Discount Price Col
        $paymentAttributeDiscountPrice = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpDiscountPriceCol,
            "attribute_value" => $vendorSubscriptionPlanData['discount_price']
        ];
        $this->paymentAttributeService->save($paymentAttributeDiscountPrice);
    }

    private function savePaymentAttrMostPopularCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Is Most Popular Plan Col
        $paymentAttributeMostPopular = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpIsMostPopularPlanCol,
            "attribute_value" => $vendorSubscriptionPlanData['is_most_popular_plan'] == false ? '0' : '1'
        ];
        $this->paymentAttributeService->save($paymentAttributeMostPopular);
    }

    private function savePaymentAttrStatusCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Status Col
        $paymentAttributeStatus = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpStatusCol,
            "attribute_value" => $vendorSubscriptionPlanData['status'] == false ? '0' : '1'
        ];
        $this->paymentAttributeService->save($paymentAttributeStatus);
    }

    private function savePaymentAttrCurrencyCol($core_key, $vendorSubscriptionPlanData)
    {
        // save payment attributes table For Currency Col
        $paymentAttributeCurrency = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $core_key,
            "attribute_key" => Constants::pmtAttrVendorSpCurrencyCol,
            "attribute_value" => $vendorSubscriptionPlanData['currency_id']
        ];
        $this->paymentAttributeService->save($paymentAttributeCurrency);
    }

    private function updateCoreKey($vendorSubscriptionPlanData)
    {
        // update core key table
        $coreKey = [
            "name" => $vendorSubscriptionPlanData['in_app_purchase_prd_id'],
            "description" => $vendorSubscriptionPlanData['in_app_purchase_prd_id']
        ];
        $core_key_id = CoreKey::where('core_keys_id', $vendorSubscriptionPlanData->core_keys_id)->first()->id;
        $this->coreKeyService->update($core_key_id, $coreKey);
    }

    private function updatePaymentInfo($id, $vendorSubscriptionPlanData)
    {
        // update payment info table
        $paymentInfo = $this->get($id);
        $paymentInfo->core_keys_id = $vendorSubscriptionPlanData->core_keys_id;
        $paymentInfo->payment_id = Constants::vendorSubscriptionPlanPaymentId;
        if (isset($vendorSubscriptionPlanData->title) && ! empty($vendorSubscriptionPlanData->title)) {
            $paymentInfo->value = $vendorSubscriptionPlanData->title;
        }
        if (isset($vendorSubscriptionPlanData->updated_user_id) && ! empty($vendorSubscriptionPlanData->updated_user_id)) {
            $paymentInfo->updated_user_id = $vendorSubscriptionPlanData->updated_user_id;
        } else {
            $paymentInfo->updated_user_id = Auth::user()->id;
        }
        $paymentInfo->update();
    }

    private function updatePaymentAttrDurationCol($vendorSubscriptionPlanData)
    {
        // update payment attributes table For Duration Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpDurationCol;
        $conds['core_keys_id'] = $vendorSubscriptionPlanData->core_keys_id;
        $paymentAttributeDuration = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeDurationData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $vendorSubscriptionPlanData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrVendorSpDurationCol,
            "attribute_value" => $vendorSubscriptionPlanData['duration']

        ];
        if ($paymentAttributeDuration) {
            $this->paymentAttributeService->update($paymentAttributeDuration->id, $paymentAttributeDurationData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeDurationData);
        }
    }

    private function updatePaymentAttrSalesPriceCol($vendorSubscriptionPlanData)
    {
        $conds['attribute_key'] = Constants::pmtAttrVendorSpSalePriceCol;
        $conds['core_keys_id'] = $vendorSubscriptionPlanData->core_keys_id;
        $paymentAttributeSalePrice = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeSalePriceData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $vendorSubscriptionPlanData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrVendorSpSalePriceCol,
            "attribute_value" => $vendorSubscriptionPlanData['sale_price']

        ];
        if ($paymentAttributeSalePrice) {
            $this->paymentAttributeService->update($paymentAttributeSalePrice->id, $paymentAttributeSalePriceData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeSalePriceData);
        }
    }

    private function updatePaymentAttrDiscountPriceCol($vendorSubscriptionPlanData)
    {
        // update payment attributes table For Discount Price Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpDiscountPriceCol;
        $conds['core_keys_id'] = $vendorSubscriptionPlanData->core_keys_id;
        $paymentAttributeDiscountPrice = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeDiscountPriceData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $vendorSubscriptionPlanData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrVendorSpDiscountPriceCol,
            "attribute_value" => $vendorSubscriptionPlanData['discount_price']

        ];
        if ($paymentAttributeDiscountPrice) {
            $this->paymentAttributeService->update($paymentAttributeDiscountPrice->id, $paymentAttributeDiscountPriceData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeDiscountPriceData);
        }
    }

    private function updatePaymentAttrStatusCol($id, $vendorSubscriptionPlanData)
    {
        // update payment attributes table For Status Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpStatusCol;
        $conds['core_keys_id'] = $id;
        $paymentAttributeStatus = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeStatusData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $id,
            "attribute_key" => Constants::pmtAttrVendorSpStatusCol,
            "attribute_value" => $vendorSubscriptionPlanData['status'] == 0 ? '0' : '1'

        ];
        if ($paymentAttributeStatus) {
            $this->paymentAttributeService->update($paymentAttributeStatus->id, $paymentAttributeStatusData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeStatusData);
        }
    }

    private function updatePaymentAttrMostPopularCol($vendorSubscriptionPlanData)
    {
        // update payment attributes table For Is Most Popular Plan Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
        $conds['core_keys_id'] = $vendorSubscriptionPlanData->core_keys_id;
        $paymentAttributeMostPopular = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeMostPopularData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $vendorSubscriptionPlanData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrVendorSpIsMostPopularPlanCol,
            "attribute_value" => $vendorSubscriptionPlanData['is_most_popular_plan'] == 0 ? '0' : '1'

        ];
        if ($paymentAttributeMostPopular) {
            $this->paymentAttributeService->update($paymentAttributeMostPopular->id, $paymentAttributeMostPopularData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeMostPopularData);
        }
    }

    private function updatePaymentAttrCurrencyCol($vendorSubscriptionPlanData)
    {
        // update payment attributes table For Currency Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpCurrencyCol;
        $conds['core_keys_id'] = $vendorSubscriptionPlanData->core_keys_id;
        $paymentAttributeCurrency = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeCurrencyData = [
            "payment_id" => Constants::vendorSubscriptionPlanPaymentId,
            "core_keys_id" => $vendorSubscriptionPlanData['core_keys_id'],
            "attribute_key" => Constants::pmtAttrVendorSpCurrencyCol,
            "attribute_value" => $vendorSubscriptionPlanData['currency_id']

        ];
        if ($paymentAttributeCurrency) {
            $this->paymentAttributeService->update($paymentAttributeCurrency->id, $paymentAttributeCurrencyData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeCurrencyData);
        }
    }
}
