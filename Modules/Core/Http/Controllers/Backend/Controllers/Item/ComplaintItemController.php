<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Services\Item\ComplaintItemService;

class ComplaintItemController extends Controller
{
    const parentPath = "complaint_item/complaint_report/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = 'complaint_item_report.index';
    const createRoute = "complaint_item_report.create";
    const editRoute = "complaint_item_report.edit";

    protected $complaintItemService;

    public function __construct(ComplaintItemService $complaintItemService)
    {
        $this->complaintItemService = $complaintItemService;
    }

    public function index(Request $request)
    {
        $dataArr = $this->complaintItemService->index($request);
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        $dataArr = $this->complaintItemService->show($id);
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::editPath, $dataArr);
    }

    public function destroy($id)
    {
        $dataArr = $this->complaintItemService->destroy($id);
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function statusChange($id){

        $dataArr = $this->complaintItemService->statusChange($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function csvExport(){
        // filename
		return $this->complaintItemService->csvExport();
    }

    public function screenDisplayUiStore(Request $request) {
        $parameter = ['page' => $request->current_page];
        $this->roleService->makeColumnHideShown($request);
        $msg = __('core__be_screen_display_ui_updated');
        return redirect()->back();
    }
}
