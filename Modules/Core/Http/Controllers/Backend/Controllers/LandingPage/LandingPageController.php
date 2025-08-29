<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\LandingPage;

use Inertia\Inertia;
use App\Config\ps_constant;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Entities\PsxLandingPage;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\LandingPageService;
use Modules\Core\Http\Requests\StoreLandingPageRequest;
use Modules\Core\Http\Requests\UpdateLandingPageRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class LandingPageController extends PsController
{

    const parentPath = "landing_page/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "landing_page.index";
    const createRoute = "landing_page.create";
    const editRoute = "landing_page.edit";
    const showLanding = self::parentPath."Show";

    protected $permissionService, $landingPageService, $dangerFlag,$editAbility,$viewAnyAbility, $code, $coreFieldFilterSettingService;

    public function __construct(PermissionServiceInterface $permissionService, LandingPageService $landingPageService, CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        parent::__construct();

        $this->landingPageService = $landingPageService;
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
        $this->dangerFlag = Constants::danger;
        $this->editAbility = Constants::editAbility;
        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->code = Constants::landingPage;
        $this->permissionService = $this->permissionService;
    }

    public function index()
    {

        $checkPermission = $this->landingPageService->checkPermission($this->viewAnyAbility, PsxLandingPage::class, "admin.index");

        if($checkPermission){
            return redirect()->route('admin.index');
        }

        $checkEditPermission = $this->permissionService->permissionControl(Constants::landingPageModule, ps_constant::updatePermission);

        $dataArr = $this->landingPageService->index();

        $dataArr['checkEditPermission'] = $checkEditPermission;

        if(empty($dataArr['landing_page'])){
            return renderView(self::createPath, $dataArr);
        }
        return renderView(self::editPath, $dataArr);
    }

    // public function showLanding()
    // {
    //     $dataArr = $this->landingPageService->index();

    //     return renderView(self::showLanding, $dataArr);
    // }

    public function store(StoreLandingPageRequest $request)
    {
        $response = $this->landingPageService->store($request);

        // if have error
        if ($response){
            $msg = $response;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        $msg = 'The landing page has been updated successfully.';
        return redirectView(self::indexRoute, $msg);
    }

    public function update(Request $request)
    {
        // validation start
        $errors = validateForCustomField($this->code,$request->landing_page_relation);

        $coreFieldsIds = [];
        $errors = [];

        $cond['module_name'] = $this->code;
        $cond['mandatory'] = 1;
        $cond['is_core_field'] = 1;

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);

        foreach ($coreFields as $key => $value){
            if (str_contains($value->field_name,"@@")) {
                $originFieldName = strstr($value->field_name,"@@",true);
            } else {
                $originFieldName = $value->field_name;
            }
            array_push($coreFieldsIds,$originFieldName);

        }

        $validationArr = [];

        if(in_array('title',$coreFieldsIds)){
            $validationArr['title'] = 'required';
        }

        if(in_array('description',$coreFieldsIds)){
            $validationArr['description'] = 'required';
        }

        if(in_array('gps_link',$coreFieldsIds)){
            $validationArr['gps_link'] = 'required';
        }

        if(in_array('ios_link',$coreFieldsIds)){
            $validationArr['ios_link'] = 'required';
        }

        if(in_array('yt_link',$coreFieldsIds)){
            $validationArr['yt_link'] = 'required';
        }

        if(in_array('fb_link',$coreFieldsIds)){
            $validationArr['fb_link'] = 'required';
        }

        if(in_array('tw_link',$coreFieldsIds)){
            $validationArr['tw_link'] = 'required';
        }


        $validationArr['cover'] = 'nullable|sometimes|image';
        $validationArr['logo'] = 'nullable|sometimes|image';

        $validator = Validator::make($request->all(),$validationArr);

        if ($validator->fails()) {
            return redirect()->route(self::indexRoute)->with('landing_page_relation_errors',$errors)
                ->withErrors($validator)
                ->withInput();
        } else {

            if (collect($errors)->isNotEmpty()){
                return redirect()->route(self::indexRoute)->with('landing_page_relation_errors',$errors);
            }
        }

        // validation end

        $response = $this->landingPageService->update(1, $request);

        // if have error
        if ($response){
            $msg = $response;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        $msg = 'The landing page has been updated successfully.';
        return redirectView(self::indexRoute, $msg);

    }

}
