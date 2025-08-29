<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Payment;

use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\StoreCoreKeyRequest;
use Modules\Core\Http\Requests\UpdateCoreKeyRequest;
use Modules\Core\Http\Services\Configuration\CoreKeyService;
use Modules\Core\Http\Services\Financial\CoreKeyPaymentRelationService;
use Modules\Payment\Http\Services\PaymentSettingService;


class VendorPaymentCoreKeyController extends Controller
{
    const parentPath = "Pages/vendor/views/payment_lists/core_key/";

    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const createRoute = "vendor_payment_core_key.create";
    const editRoute = "vendor_payment_core_key.edit";

    protected $coreKeyService, $successFlag, $dangerFlag, $code, $coreKeyPaymentRelationService, $paymentSettingService;

    public function __construct(CoreKeyService $coreKeyService, CoreKeyPaymentRelationService $coreKeyPaymentRelationService, PaymentSettingService $paymentSettingService)
    {
        $this->coreKeyService = $coreKeyService;
        $this->coreKeyPaymentRelationService = $coreKeyPaymentRelationService;
        $this->paymentSettingService = $paymentSettingService;

        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;

        $this->code = Constants::payment;
    }

    public function create($payment_id)
    {
        $dataArr['payment_id'] = $payment_id;
        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreCoreKeyRequest $request, $payment_id)
    {
        //store core key
        $coreKey = $this->coreKeyService->save($request, $this->code);

        // store core key payment relation
        $coreKeyPaymentRelation = new \stdClass();
        $coreKeyPaymentRelation->core_keys_id = $coreKey->core_keys_id;
        $coreKeyPaymentRelation->payment_id = $payment_id;
        $coreKeyPaymentRelation = $this->coreKeyPaymentRelationService->save((array)$coreKeyPaymentRelation);

        // store payment info relation
        $paymentInfo = new \stdClass();
        $paymentInfo->core_keys_id = $coreKey->core_keys_id;
        $paymentInfo->payment_id = $payment_id;
        $paymentInfo = $this->paymentSettingService->store($paymentInfo);

        // if have error
        if (isset($coreKey['error'])) {
            $msg = $coreKey;
            return redirectView(self::createRoute, $msg, $this->dangerFlag, $payment_id);
        }

        return redirect()->back();
    }

    public function edit($payment_id, $id)
    {
        $dataArr['coreKey'] = $this->coreKeyService->get($id);
        $dataArr['payment_id'] = $payment_id;
        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateCoreKeyRequest $request, $payment_id, $id)
    {
        //store data in database
        $coreKey = $this->coreKeyService->update($id, $request);

        // if have error
        if (isset($coreKey['error'])) {
            $msg = $coreKey;
            return redirectView(self::createRoute, $msg, $this->dangerFlag, $payment_id);
        }

        return redirect()->back();
    }
}
