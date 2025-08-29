<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Order;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\OrderTransactionReportService;

class OrderTransactionReportController extends PsController
{
    const parentPath = "order_transaction_report/";
    const indexPath = self::parentPath . "Index";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "order_transaction_report.index";
    const editRoute = "order_transaction_report.edit";
    const promotePath = self::parentPath . 'Promote';

    protected $orderTransactionReportService, $successFlag, $dangerFlag;

    public function __construct(OrderTransactionReportService $orderTransactionReportService)
    {
        parent::__construct();

        $this->orderTransactionReportService = $orderTransactionReportService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
    }

    public function promote($id)
    {
        $dataArr = [
            'item_id' => $id
        ];
        return renderView(self::promotePath, $dataArr);
    }

    public function index(Request $request)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::orderTransactionReportModule, ps_constant::readPermission, $vendorId);

        return $this->orderTransactionReportService->index($request);
    }

    public function edit($id)
    {
       return  $this->orderTransactionReportService->edit($id);

        // $checkPermission = $dataArr["checkPermission"];
        // if (!empty($checkPermission)){
        //     return $checkPermission;
        // }

        // return renderView(self::editPath, $dataArr);
    }

    // public function store(Request $request)
    // {
    //     $paid_item = $this->paidItemService->store($request);

    //     // if have error
    //     if (isset($paid_item['error'])) {
    //         $msg = $paid_item['error'];
    //         return redirectView(self::indexRoute, $msg, $this->dangerFlag);
    //     }

    //     return redirect()->back();
    // }

    // public function update(Request $request, $id)
    // {
    //     $paid_item = $this->paidItemService->update($id, $request);

    //     // if have error
    //     if ($paid_item) {
    //         $msg = $paid_item;
    //         return redirectView(self::editRoute, $msg, $this->dangerFlag, $id);
    //     }

    //     return redirect()->back();
    // }

    // public function statusChange($id)
    // {

    //     $this->paidItemService->makePublishOrUnpublish($id);

    //     // $msg = 'The status of paid_item row has been updated successfully.';
    //     $msg = __('core__be_status_attribute_updated',['attribute'=>__('core__be_paid_item_label')]);
    //     return redirectView(self::indexRoute, $msg);
    // }

    public function OrderTransactionReportCsvExport()
    {
        return $this->orderTransactionReportService->OrderTransactionReportCsvExport();
    }
}
