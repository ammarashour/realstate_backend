<?php

namespace Modules\Core\Http\Services\Financial;

use App\Config\Cache\PaymentInfoCache;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Http\Contracts\Financial\PackageInAppPurchaseServiceInterface;
use App\Http\Contracts\Financial\CoreKeyPaymentRelationServiceInterface;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class PackageInAppPurchaseSettingService extends PsService implements PackageInAppPurchaseServiceInterface
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

    public function save($PackageIAPData)
    {

        DB::beginTransaction();

        try {
            $coreKeysId = $this->saveCoreKey($PackageIAPData);

            $this->savePaymentRelation($coreKeysId);

            $this->savePaymentInfo($coreKeysId, $PackageIAPData);

            $this->saveTypeColinPaymentAttr($coreKeysId, $PackageIAPData);

            $this->saveCountColinPaymentAttr($coreKeysId, $PackageIAPData);

            $this->savePriceColinPaymentAttr($coreKeysId, $PackageIAPData);

            $this->saveStatusColinPaymentAttr($coreKeysId, $PackageIAPData);

            $this->saveCurrencyColinPaymentAttr($coreKeysId, $PackageIAPData);

            PsCache::clear(PaymentInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $PackageIAPData)
    {

        DB::beginTransaction();

        try {

            $this->updateCoreKey($id, $PackageIAPData);

            $this->updatePaymentInfo($id, $PackageIAPData);

            $this->updateTypeColInPaymentAttr($id, $PackageIAPData);

            $this->updateCountColInPaymentAttr($id, $PackageIAPData);

            $this->updatePriceColInPaymentAttr($id, $PackageIAPData);

            $this->updateStatusColInPaymentAttr($PackageIAPData->core_keys_id, $PackageIAPData->toArray());

            $this->updateCurrencyColInPaymentAttr($id, $PackageIAPData);

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
        try {

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

            return $dataArr;


            return $dataArr;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $VendorStatus = $this->prepareUpdateStatusData($status);
            $this->updateStatusColInPaymentAttr($id, $VendorStatus);

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

    private function saveCoreKey($PackageIAPData)
    {
        $coreKey = new \stdClass();
        $coreKey->name = $PackageIAPData['in_app_purchase_prd_id'];
        $coreKey->description = $PackageIAPData['in_app_purchase_prd_id'];
        $core_key = $this->coreKeyService->save($coreKey, Constants::payment);
        $coreKeysId = $core_key->core_keys_id;
        return $coreKeysId;
    }

    private function savePaymentInfo($coreKeysId, $PackageIAPData)
    {
        // save payment info table
        $paymentInfo = new PaymentInfo();

        $paymentInfo->core_keys_id = $coreKeysId;

        $paymentInfo->payment_id = Constants::packageInAppPurchasePaymentId;

        $paymentInfo->value = isset($PackageIAPData['description']) && !empty($PackageIAPData['description']) ? $PackageIAPData['description'] : $paymentInfo->value;

        $paymentInfo->added_user_id = isset($PackageIAPData->added_user_id) && !empty($PackageIAPData->added_user_id) ? $PackageIAPData->added_user_id : Auth::user()->id;

        $paymentInfo->save();
    }

    private function savePaymentRelation($coreKeysId)
    {
        // save core key payment relations table
        $coreKeyPaymentRelation = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
        ];
        $this->coreKeyPaymentRelationService->save($coreKeyPaymentRelation);
    }

    private function saveTypeColinPaymentAttr($coreKeysId, $PackageIAPData)
    {
        // save payment attributes table For Type Col
        $paymentAttributeType = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapTypeCol,
            'attribute_value' => $PackageIAPData['type']
        ];

        $this->paymentAttributeService->save($paymentAttributeType);
    }
    private function saveCountColinPaymentAttr($coreKeysId, $PackageIAPData)
    {
        // save payment attributes table For Post Count Col
        $paymentAttributeCount = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapCountCol,
            'attribute_value' => $PackageIAPData['count']
        ];
        $this->paymentAttributeService->save($paymentAttributeCount);
    }

    private function savePriceColinPaymentAttr($coreKeysId, $PackageIAPData)
    {
        // save payment attributes table For Price Col
        $paymentAttributePrice = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapPriceCol,
            'attribute_value' => $PackageIAPData['price']
        ];
        $this->paymentAttributeService->save($paymentAttributePrice);
    }

    private function saveStatusColinPaymentAttr($coreKeysId, $PackageIAPData)
    {
        // save payment attributes table For Status Col
        $paymentAttributeStatus = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
            'attribute_value' => $PackageIAPData['status']
        ];
        $this->paymentAttributeService->save($paymentAttributeStatus);
    }

    private function saveCurrencyColinPaymentAttr($coreKeysId, $PackageIAPData)
    {
        // save payment attributes table For Currency Col
        $paymentAttributeCurrency = [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapCurrencyCol,
            'attribute_value' => $PackageIAPData['currency_id']
        ];
        $this->paymentAttributeService->save($paymentAttributeCurrency);
    }

    private function updateCoreKey($id, $PackageIAPData)
    {

        // update core key table
        $coreKey = new \stdClass();
        $coreKey->name = $PackageIAPData['in_app_purchase_prd_id'];
        $coreKey->description = $PackageIAPData['in_app_purchase_prd_id'];
        $core_key_id = CoreKey::where('core_keys_id', $PackageIAPData['core_keys_id'])->first()->id;
        $this->coreKeyService->update($PackageIAPData['core_keys_id'], $coreKey);
    }

    private function updatePaymentInfo($id, $PackageIAPData)
    {
        // update payment info table
        $paymentInfo = $this->get($id);
        $paymentInfo->core_keys_id = $PackageIAPData['core_keys_id'];
        $paymentInfo->payment_id = Constants::packageInAppPurchasePaymentId;
        if (isset($PackageIAPData['description']) && !empty($PackageIAPData['description']))
            $paymentInfo->value = $PackageIAPData['description'];
        if (isset($PackageIAPData['updated_user_id']) && !empty($PackageIAPData['updated_user_id']))
            $paymentInfo->updated_user_id = $PackageIAPData['updated_user_id'];
        else
            $paymentInfo->updated_user_id = Auth::user()->id;
        $paymentInfo->update();
    }

    private function updateTypeColInPaymentAttr($id, $PackageIAPData)
    {
        // update payment attributes table For Type Col
        $conds['attribute_key'] = Constants::pmtAttrPackageIapTypeCol;
        $conds['core_keys_id'] = $PackageIAPData['core_keys_id'];
        $paymentAttributeType = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeTypeData = [
            'core_keys_id' => $PackageIAPData['core_keys_id'],
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapTypeCol,
            'attribute_value' => $PackageIAPData['type'],
        ];
        if ($paymentAttributeType) {
            $this->paymentAttributeService->update($paymentAttributeType->id, $paymentAttributeTypeData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeTypeData);
        }
    }

    private function updateCountColInPaymentAttr($id, $PackageIAPData)
    {
        // update payment attributes table For Post Count Col
        $conds['attribute_key'] = Constants::pmtAttrPackageIapCountCol;
        $conds['core_keys_id'] = $PackageIAPData['core_keys_id'];
        $paymentAttributeCount = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeCountData = [
            'core_keys_id' => $PackageIAPData['core_keys_id'],
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapCountCol,
            'attribute_value' => $PackageIAPData['count'],
        ];
        if ($paymentAttributeCount) {
            $this->paymentAttributeService->update($paymentAttributeCount->id, $paymentAttributeCountData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeCountData);
        }
    }

    private function updatePriceColInPaymentAttr($id, $PackageIAPData)
    {
        // update payment attributes table For Price Col
        $conds['attribute_key'] = Constants::pmtAttrPackageIapPriceCol;
        $conds['core_keys_id'] = $PackageIAPData['core_keys_id'];
        $paymentAttributePrice = $this->paymentAttributeService->get(null, $conds);

        $paymentAttributePriceData = [
            'core_keys_id' => $PackageIAPData['core_keys_id'],
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapPriceCol,
            'attribute_value' => $PackageIAPData['price'],
        ];
        if ($paymentAttributePrice) {
            $this->paymentAttributeService->update($paymentAttributePrice->id, $paymentAttributePriceData);
        } else {
            $this->paymentAttributeService->save($paymentAttributePriceData);
        }
    }
    private function updateStatusColInPaymentAttr($id, $PackageIAPData)
    {
        // update payment attributes table For Status Col
        $conds['attribute_key'] = Constants::pmtAttrPackageIapStatusCol;
        $conds['core_keys_id'] = $id;
        $paymentAttributeStatus = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeStatusData = [
            'core_keys_id' => $id,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
            'attribute_value' => $PackageIAPData['status'] == 0 ? '0' : '1',
        ];

        if ($paymentAttributeStatus) {
            $this->paymentAttributeService->update($paymentAttributeStatus->id, $paymentAttributeStatusData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeStatusData);
        }
    }
    private function updateCurrencyColInPaymentAttr($id, $PackageIAPData)
    {
        // update payment attributes table For Currency Col
        $conds['attribute_key'] = Constants::pmtAttrPackageIapCurrencyCol;
        $conds['core_keys_id'] = $PackageIAPData['core_keys_id'];
        $paymentAttributeCurrency = $this->paymentAttributeService->get(null, $conds);
        $paymentAttributeCurrencyData = [
            'core_keys_id' => $PackageIAPData['core_keys_id'],
            'payment_id' => Constants::packageInAppPurchasePaymentId,
            'attribute_key' => Constants::pmtAttrPackageIapCurrencyCol,
            'attribute_value' => $PackageIAPData['currency_id'],
        ];
        if ($paymentAttributeCurrency) {
            $this->paymentAttributeService->update($paymentAttributeCurrency->id, $paymentAttributeCurrencyData);
        } else {
            $this->paymentAttributeService->save($paymentAttributeCurrencyData);
        }
    }



    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------

}
