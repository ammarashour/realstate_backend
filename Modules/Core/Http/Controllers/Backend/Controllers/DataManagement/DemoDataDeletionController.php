<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\DataManagement;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\DataManagement\DemoDataDeletionService;

class DemoDataDeletionController extends PsController
{
    const parentPath = "demo_data_deletion/";
    const indexPath = self::parentPath . "Index";
    const indexRoute = "demo_data_deletion.index";

    protected $demoDataDeletionService;

    public function __construct(DemoDataDeletionService $demoDataDeletionService)
    {
        parent::__construct();

        $this->demoDataDeletionService = $demoDataDeletionService;
    }

    public function index()
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::dataReset, ps_constant::readPermission, Auth::id());

        $dataArr = $this->demoDataDeletionService->index();
        return renderView(self::indexPath, $dataArr);
    }

    public function destroy()
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::dataReset, ps_constant::deletePermission, Auth::id());

        $dataArr = $this->demoDataDeletionService->destroy();
        return renderView(self::indexPath, $dataArr);

    }
}
