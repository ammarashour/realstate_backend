<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\About;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Services\AboutService;
use Modules\Core\Http\Requests\StoreAboutRequest;
use Modules\Core\Http\Requests\UpdateAboutRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class AboutControllerBk extends Controller
{
    const parentPath = "about/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "about.index";
    const createRoute = "about.create";
    const editRoute = "about.edit";

    protected $aboutService, $coreFieldFilterSettingService;

    public function __construct(AboutService $aboutService, CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        $this->aboutService = $aboutService;
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
    }

    public function index()
    {
        $dataArr = $this->aboutService->index(self::indexRoute);
        $checkPermission = $dataArr["checkPermission"];
        if (!empty($checkPermission)){
            return $checkPermission;
        }else {

            if(empty($dataArr['about'])){
                return renderView(self::createPath,$dataArr);
            }
            return renderView(self::editPath, $dataArr);
       }

    }

    public function store(StoreAboutRequest $request)
    {
        $about = $this->aboutService->create($request);

        // if have error
        if ($about){
            $msg = $about;
            return redirectView(self::indexRoute, $msg, Constants::danger);
        }

        $msg = __('core__be_about_updated');
        return redirectView(self::indexRoute, $msg);
    }

    public function update(UpdateAboutRequest $request, $id)
    {
        $about = $this->aboutService->update($id,$request);

        // if have error
        if ($about){
            $msg = $about;
            return redirectView(self::indexRoute, $msg, Constants::danger);
        }

        $msg = __('core__be_about_updated');
        return redirectView(self::indexRoute, $msg);
    }
}
