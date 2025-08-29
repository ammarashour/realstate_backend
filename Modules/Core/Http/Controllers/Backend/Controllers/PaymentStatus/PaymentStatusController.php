<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\PaymentStatus;

use Inertia\Inertia;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\PaymentStatus;
use Modules\Core\Http\Services\PaymentStatusService;
use Modules\Core\Http\Requests\StorePaymentStatusRequest;
use Modules\Core\Http\Requests\UpdatePaymentStatusRequest;

class PaymentStatusController extends Controller
{

    const parentPath = "payment_status/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "payment_status.index";
    const createRoute = "payment_status.create";
    const editRoute = "payment_status.edit";

    protected $paymentStatusService, $successFlag, $dangerFlag, $csvFile, $warningFlag;

    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->paymentStatusService = $paymentStatusService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->csvFile = Constants::csvFile;
    }

    public function index()
    {
        $dataArr = $this->paymentStatusService->index();
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        return renderView(self::createPath);
    }

    public function store(StorePaymentStatusRequest $request)
    {
        $paymentStatus = $this->paymentStatusService->store($request);

        // if have error
        if (isset($paymentStatus['error'])){
            $msg = $paymentStatus['error'];
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

//    public function show(PaymentStatus $payment_status)
//    {
//        return redirect()->route('payment_status.edit', $payment_status);
//    }

    public function edit($id)
    {
        $dataArr = $this->paymentStatusService->edit($id);
        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdatePaymentStatusRequest $request, $id)
    {
        $paymentStatus = $this->paymentStatusService->update($id, $request);

        // if have error
        if (isset($paymentStatus['error'])){
            $msg = $paymentStatus['error'];
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $dataArr = $this->paymentStatusService->destroy($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

    }
}
