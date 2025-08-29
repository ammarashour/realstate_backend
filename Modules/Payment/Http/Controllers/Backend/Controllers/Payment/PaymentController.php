<?php

namespace Modules\Payment\Http\Controllers\Backend\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Payment\Http\Requests\StorePaymentReqeust;
use Modules\Payment\Http\Requests\UpdatePaymentReqeust;
use Modules\Payment\Http\Services\PaymentService;

class PaymentController extends Controller
{
    const parentPath = "payment/payment/";

    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "payment.index";
    const createRoute = "payment.create";
    const editRoute = "payment.edit";

    protected $paymentService, $successFlag, $dangerFlag, $code;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;

        $this->code = Constants::payment;
    }

    public function index(Request $request)
    {
        $dataArr = $this->paymentService->index($request);
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $dataArr = $this->paymentService->create();
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::createPath, $dataArr);
    }

    public function store(StorePaymentReqeust $request)
    {
        $payment = $this->paymentService->store($request);
        // if have error
        if (isset($payment['error'])) {
            $msg = $payment['error'];
            return redirectView(self::createRoute, $msg, $this->dangerFlag);
        }

        $dataArr = ['payment' => $payment];
        return redirectView(self::editRoute, $dataArr, $this->successFlag, $payment->id);

    }

    public function update(UpdatePaymentReqeust $request, $id)
    {
        $payment = $this->paymentService->update($id, $request);

        // if have error
        if (isset($payment['error'])) {
            $msg = $payment['error'];
            return redirectView(self::editRoute, $msg, $this->dangerFlag, $payment->id);
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $dataArr = $this->paymentService->edit($id);
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::editPath, $dataArr);
    }

    public function statusChange($id)
    {
        $this->paymentService->makePublishOrUnpublish($id);

        // $msg = 'The status of payment row has been updated successfully.';
        $msg = __('core__be_status_attribute_updated',['attribute'=>__('core__be_payment_status_label')]);
        return redirectView(self::indexRoute, $msg);
    }

    public function screenDisplayUiStore(Request $request) {
        $parameter = ['page' => $request->current_page];

        $this->paymentService->makeColumnHideShown($request);

        // $msg = 'ScreenDisplay UiSetting is updated.';
        $msg = __('core__be_screen_display_ui_updated');
        // return redirectView(self::indexRoute, $msg,null,$parameter);
        return redirect()->back();
    }
}
