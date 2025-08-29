<?php

namespace Modules\Core\Http\Services\Financial;

use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Contracts\Financial\CoreKeyPaymentRelationServiceInterface;
use App\Http\Contracts\Financial\OfflinePaymentSettingServiceInterface;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;
//old
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;

class OfflinePaymentSettingService extends PsService implements OfflinePaymentSettingServiceInterface
{
    protected $iconImgType;

    protected $fileKey;

    public function __construct(
        protected PaymentAttributeServiceInterface $paymentAttributeService,
        protected UserAccessApiTokenService $userAccessApiTokenService,
        protected ImageServiceInterface $imageService,
        protected CoreKeyPaymentRelationServiceInterface $coreKeyPaymentRelationService,
        protected PaymentService $paymentService,
        protected CoreKeyServiceInterface $coreKeyService,
        protected PaymentSettingService $paymentSettingService,
        protected PaymentInfoServiceInterface $paymentInfoService
    ) {}

    public function save($offlinePaymentSettingData, $offlinePaymentSettingImage)
    {
        DB::beginTransaction();

        try {
            //save coreKeyService
            $coreKeyData = $this->prepareCoreKeyData($offlinePaymentSettingData);
            $savedCoreKey = $this->coreKeyService->save($coreKeyData, Constants::payment);

            //save coreKeyPaymentRelation
            $coreKeyPaymentRelationData = $this->prepareCoreKeyPaymentRelationData($savedCoreKey->core_keys_id);
            $this->coreKeyPaymentRelationService->save($coreKeyPaymentRelationData);

            //save PaymentInfo
            //Created New Service File
            $paymentInfoData = $this->preparePaymentInfoData($savedCoreKey->core_keys_id, $offlinePaymentSettingData['description']);
            $savedPaymentInfo = $this->paymentInfoService->save($paymentInfoData);

            //save Offline Payment Image
            $imageData = $this->prepareSaveImageData($savedPaymentInfo->id);
            $this->imageService->save($offlinePaymentSettingImage, $imageData);

            //Save PaymentAttribute
            $paymentAttributeStatus = $this->preparePaymentAttributeData($savedCoreKey->core_keys_id, Constants::pmtAttrOfflinePaymentStatusCol, $offlinePaymentSettingData['status']);
            $this->paymentAttributeService->save($paymentAttributeStatus);

            DB::commit();

            return $savedPaymentInfo;

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $offlinePaymentSettingData)
    {
        DB::beginTransaction();

        try {
            $coreKeysId = $offlinePaymentSettingData['core_keys_id'];
            //update coreKeyService
            $coreKeyConds = [
                'core_keys_id' => $coreKeysId,
            ];
            $coreKey = $this->coreKeyService->get(null, $coreKeyConds);
            $coreKeyData = $this->prepareCoreKeyData($offlinePaymentSettingData);
            $this->coreKeyService->update($coreKey->id, $coreKeyData);

            //update PaymentInfo
            $paymentInfoData = $this->preparePaymentInfoData($coreKeysId, $offlinePaymentSettingData['description']);
            $savedPaymentInfo = $this->paymentInfoService->update($id, $paymentInfoData);

            // update payment_attributes table For Status Col
            $conds = [
                'payment_id' => Constants::offlinePaymentId,
                'core_keys_id' => $coreKeysId,
                'attribute_key' => Constants::pmtAttrOfflinePaymentStatusCol,
            ];
            $paymentAttribute = $this->paymentAttributeService->get(null, $conds);
            $paymentAttributeData = $this->preparePaymentAttributeData($coreKeysId, Constants::pmtAttrOfflinePaymentStatusCol, $offlinePaymentSettingData['status']);
            $this->paymentAttributeService->update($paymentAttribute->id, $paymentAttributeData);

            DB::commit();

            return $savedPaymentInfo;

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            //prepare data for delete
            $paymentInfo = $this->paymentInfoService->get($id);
            $coreKeysId = $paymentInfo->core_keys_id;
            $this->paymentInfoService->delete($id);

            // core key
            $coreKeyConds = [
                'core_keys_id' => $coreKeysId,
            ];
            $coreKey = $this->coreKeyService->get(null, $coreKeyConds);
            $this->coreKeyService->delete($coreKey->id);

            // core key payment relation
            $ckprConds = [
                'payment_id' => Constants::offlinePaymentId,
                'core_keys_id' => $coreKeysId,
            ];
            $this->coreKeyPaymentRelationService->delete(null, $ckprConds);

            // payment attribute
            $pmtAttrConds = [
                'payment_id' => Constants::offlinePaymentId,
                'core_keys_id' => $coreKeysId,
                'attribute_key' => Constants::pmtAttrOfflinePaymentStatusCol,
            ];
            $this->paymentAttributeService->delete(null, $pmtAttrConds);

            // image
            $this->imageService->deleteAll($id, Constants::offlinePaymentIconImgType);

            $dataArr = [
                'msg' => __('core__be_delete_success', ['attribute' => $coreKey->name]),
                'flag' => Constants::danger,
            ];
            DB::commit();

            return $dataArr;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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

    public function setStatus($id, $paymentAttributeStatus)
    {
        try {
            // prepare attribute data
            $paymentAttributeData = $this->prepareAttributeValue($paymentAttributeStatus);

            //update attribute value
            $this->paymentAttributeService->update($id, $paymentAttributeData);

            return [
                'msg' => __('core__be_status_updated'),
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
    private function prepareAttributeValue($attributeValue)
    {
        return [
            'attribute_value' => $attributeValue,
        ];
    }

    private function prepareCoreKeyData($offlinePaymentSettingData)
    {
        return [
            'name' => $offlinePaymentSettingData['title'],
            'description' => $offlinePaymentSettingData['description'],
        ];
    }

    private function prepareCoreKeyPaymentRelationData($coreKeysId)
    {
        return [
            'core_keys_id' => $coreKeysId,
            'payment_id' => Constants::offlinePaymentId,
        ];
    }

    private function prepareSaveImageData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => Constants::offlinePaymentIconImgType,
        ];
    }

    private function preparePaymentAttributeData($coreKeysId, $attributeKey, $attributeValue)
    {
        return [
            'payment_id' => Constants::offlinePaymentId,
            'core_keys_id' => $coreKeysId,
            'attribute_key' => $attributeKey,
            'attribute_value' => $attributeValue,
        ];
    }

    private function preparePaymentInfoData($coreKeysId, $value)
    {
        return [
            'payment_id' => Constants::offlinePaymentId,
            'core_keys_id' => $coreKeysId,
            'value' => $value,
        ];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

}
