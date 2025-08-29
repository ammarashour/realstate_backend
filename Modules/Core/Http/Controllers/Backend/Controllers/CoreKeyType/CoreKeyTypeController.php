<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\CoreKeyType;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\StoreCoreKeyTypeRequest;
use Modules\Core\Http\Services\CoreKeyTypeService;
class CoreKeyTypeController extends Controller
{
    const parentPath = "core/coreKeyType/";

    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "core_key_type.index";
    const createRoute = "core_key_type.create";

    protected $coreKeyTypeService, $successFlag, $dangerFlag, $csvFile;

    public function __construct(CoreKeyTypeService $coreKeyTypeService)
    {
        $this->coreKeyTypeService = $coreKeyTypeService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
    }

    public function index(Request $request)
    {
    
        $dataArr = $this->coreKeyTypeService->index($request);
        
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $dataArr = $this->coreKeyTypeService->create();
        // check permission
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }
        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreCoreKeyTypeRequest $request)
    {
        //store data in database
        $coreKeyType = $this->coreKeyTypeService->store($request);

        // if have error
        if ($coreKeyType) {
            $msg = $coreKeyType;
            return redirectView(self::createRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }
}
