<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Localization;

use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Config\ps_constant;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use Modules\Core\Http\Requests\Localization\StoreMobileLanguageRequest;
use Modules\Core\Http\Requests\Localization\UpdateMobileLanguageRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use App\Http\Controllers\PsController;
use Modules\Core\Entities\Localization\MobileLanguage;
use Modules\Core\Transformers\Backend\Model\Localization\MobileLanguageWithKeyResource;
use Illuminate\Support\Facades\Session;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;

class MobileLanguageController extends PsController
{
    private const parentPath = "mobile_language";
    private const indexPath = self::parentPath."/Index";
    private const createPath = self::parentPath."/Create";
    private const editPath = self::parentPath."/Edit";
    private const indexRoute = "mobile_language.index";
    private const createRoute = "mobile_language.create";
    private const editRoute = "mobile_language.edit";


    public function __construct(
        protected MobileLanguageServiceInterface $mobileLanguageService,         
        protected MobileLanguageStringServiceInterface $languageStringService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(MobileLanguage::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(MobileLanguage::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StoreMobileLanguageRequest $request)
    {
        try {
            $validData = $request->validated();

            $this->mobileLanguageService->save($validData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $mbLang = $this->mobileLanguageService->get($id);
        $this->handlePermissionWithModel($mbLang, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateMobileLanguageRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->mobileLanguageService->update($id, $validatedData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }


    public function destroy($id)
    {
        try {
            $language = $this->mobileLanguageService->get($id);

            $this->handlePermissionWithModel($language, Constants::deleteAbility);

            $dataArr = $this->mobileLanguageService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    // default language
    public function statusChange($id)
    {
        try {

            $language = $this->mobileLanguageService->get($id);

            $this->handlePermissionWithModel($language, Constants::editAbility);

            if($language->status == Constants::unPublish)
            {
                $status = $this->prepareStatusData($language);
    
                $this->mobileLanguageService->setStatus($id, $status);
            }

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    // enable disable language
    public function enableDisable($id)
    {
        try {

            $language = $this->mobileLanguageService->get($id);
            
            $this->handlePermissionWithModel($language, Constants::editAbility);

            if($language->status == Constants::unPublish)
            {
                $status = $this->prepareEnableData($language);
                $this->mobileLanguageService->enableDisable($id, $status);
            }

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function changeLanguage(Request $request){
        Session::put("applocale", $request->langSymbol);
        return redirect()->back();
    }


    public function screenDisplayUiStore(Request $request)
    {

        makeColumnHideShown($request);

        return redirect()->back();
    }
    
    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'location_city_id' => $request->input('city_filter') === 'all' ? null : $request->input('city_filter'),
            'order_by' => $request->input('sort_field') ?? 'added_date',
            'order_type' => $request->input('sort_order') ?? 'desc',
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate mb language data
        //$relations = ['owner', 'editor'];

        $languages = MobileLanguageWithKeyResource::collection($this->mobileLanguageService->getAll(null,null,null,$conds,false,$row));

        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::mobileLanguage, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createMobileLanguage' => 'create-mobileLanguage',
        ];

        return [
            'mobile_languages' => $languages,
            'showLanguageCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {
        $mobileLanguage = $this->mobileLanguageService->get($id);

        return[
            "mobileLanguage" => $mobileLanguage
        ];
    }

    private function prepareStatusData($mobileLanguage)
    {
        return $mobileLanguage->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }
    private function prepareEnableData($mobileLanguage)
    {
        return $mobileLanguage->enable == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }

}
