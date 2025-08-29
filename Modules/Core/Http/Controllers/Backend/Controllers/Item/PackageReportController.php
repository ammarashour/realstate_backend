<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Services\Item\PackageBoughtTransactionService;

class PackageReportController extends PsController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    const parentPath = "package_report/";
    const indexPath = self::parentPath."Index";
    const editPath = self::parentPath."Edit";
    const indexRoute = "package_report.index";
    const editRoute = "package_report.edit";

    protected $packageBoughtTransactionService, $packageService, $successFlag, $dangerFlag, $csvFile, $warningFlag;

    public function __construct(PackageBoughtTransactionService $packageBoughtTransactionService)
    {
        parent::__construct();

        $this->packageBoughtTransactionService = $packageBoughtTransactionService;

        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->csvFile = Constants::csvFile;

    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::packageReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->packageBoughtTransactionService->packageReportIndex($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::packageReportModule, ps_constant::readPermission, Auth::id());

        $relations = ['package', 'user'];

        $dataArr['transaction'] = $this->packageBoughtTransactionService->getPackageBoughtTransaction($id, null, $relations);

        // $dataArr['packages'] = $this->packageService->getPackages();

        // $dataArr = $this->packageBoughtTransactionService->packageReportShow($id);
        return renderView(self::editPath, $dataArr);
    }

    public function csvExport(){
        // filename
		return $this->packageBoughtTransactionService->packageReportCsvExport();
    }
}
