<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Item\PaidItemService;

class PaidItemController extends Controller
{
    const parentPath = "promotion_report/";
    const indexPath = self::parentPath . "Index";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "paid_item.index";
    const editRoute = "paid_item.edit";
    const promotePath = self::parentPath . 'Promote';

    protected $paidItemService, $successFlag, $dangerFlag;

    public function __construct(PaidItemService $paidItemService)
    {
        $this->paidItemService = $paidItemService;
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
        $dataArr = $this->paidItemService->index($request);

        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        $dataArr = $this->paidItemService->edit($id);

        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }

        return renderView(self::editPath, $dataArr);
    }

    public function store(Request $request)
    {
        $paid_item = $this->paidItemService->store($request);

        // if have error
        if (isset($paid_item['error'])) {
            $msg = $paid_item['error'];
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $paid_item = $this->paidItemService->update($id, $request);

        // if have error
        if ($paid_item) {
            $msg = $paid_item;
            return redirectView(self::editRoute, $msg, $this->dangerFlag, $id);
        }

        return redirect()->back();
    }

    public function statusChange($id)
    {

        $this->paidItemService->makePublishOrUnpublish($id);

        // $msg = 'The status of paid_item row has been updated successfully.';
        $msg = __('core__be_status_attribute_updated',['attribute'=>__('core__be_paid_item_label')]);
        return redirectView(self::indexRoute, $msg);
    }

    public function paidItemReportCsvExport()
    {
        return $this->paidItemService->paidItemReportCsvExport();
    }
}
