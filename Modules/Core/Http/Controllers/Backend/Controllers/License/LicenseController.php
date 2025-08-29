<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\License;

use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\StoreLicenseRequest;
use Modules\Core\Http\Requests\UpdateLicenseRequest;
use Modules\Core\Http\Services\LicenseService;

class LicenseController extends Controller
{
    const parentPath = "license/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "license.index";

    protected $licenseService, $successFlag, $dangerFlag;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
    }

    public function index()
    {
        $dataArr = $this->licenseService->index();

        if(empty($dataArr['project'])){
            return renderView(self::createPath);
        }
        return renderView(self::editPath, $dataArr);
    }

    public function store(StoreLicenseRequest $request)
    {

        $project = $this->licenseService->create($request);

        // if have error
        if ($project){
            $msg = $project;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        $msg = 'The backend configuration setting has been updated successfully.';
        return redirectView(self::indexRoute, $msg);
    }

    public function update(UpdateLicenseRequest $request, $id)
    {
        $project = $this->licenseService->update($id,$request);
        // if have error
        if ($project){
            $msg = $project;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        $msg = 'The License configuration has been updated successfully.';
        return redirectView(self::indexRoute, $msg);
    }
}
