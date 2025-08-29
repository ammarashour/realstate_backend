<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Item\SlowMovingItemService;

class SlowMovingItemReportController extends PsController
{
    const parentPath = "slow_moving_items/slow_moving_item_report/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "slow_moving_item_report.index";
    const createRoute = "slow_moving_item_report.create";
    const editRoute = "slow_moving_item_report.edit";

    protected $slowMovingItemService, $successFlag, $dangerFlag, $csvFile;

    public function __construct(SlowMovingItemService $slowMovingItemService)
    {
        parent::__construct();

        $this->slowMovingItemService = $slowMovingItemService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->csvFile = Constants::csvFile;
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::slowMovingItemReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->slowMovingItemService->slowMovingItemReportIndex($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        $dataArr = $this->slowMovingItemService->slowMovingItemReportShow($id);
        return renderView(self::editPath, $dataArr);
    }

    public function csvExport(){
        // filename
		return $this->slowMovingItemService->csvExport();
    }

}
