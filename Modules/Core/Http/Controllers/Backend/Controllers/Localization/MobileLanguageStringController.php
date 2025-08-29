<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Localization;

use Illuminate\Http\Request;
use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Requests\Localization\StoreMobileLanguageStringRequest;
use Modules\Core\Http\Requests\Localization\UpdateMobileLanguageStringRequest;
use App\Http\Controllers\PsController;
use Modules\Core\Entities\Localization\MobileLanguageString;
use Modules\Core\Transformers\Backend\Model\Localization\MobileLanguageStringWithKeyResource;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;

class MobileLanguageStringController extends PsController
{
    private const parentPath = "mobile_language_string/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "mobile_language_string.index";
    private const createRoute = "mobile_language_string.create";
    private const editRoute = "mobile_language_string.edit";

    public function __construct(
        protected MobileLanguageStringServiceInterface $mobileLanguageStringService,
        protected CoreFieldFilterSettingService $coreFieldFilterSettingService,
        protected MobileLanguageServiceInterface $mobileLanguageService
    ) {
        parent::__construct();
    }

    public function index($id, Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(MobileLanguageString::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($id, $request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create($mobileLanguageId)
    {
        // check permission start
        $this->handlePermissionWithModel(MobileLanguageString::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData($mobileLanguageId);

        return renderView(self::createPath, $dataArr);
    }

    public function store($mobileLanguage_id, StoreMobileLanguageStringRequest $request)
    {
        $routeParams = [$mobileLanguage_id];

        try {
            // Validate the request data
            $validData = $request->validated();
            $this->mobileLanguageStringService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute, null, $routeParams);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage(), $routeParams);
        }
    }

    
    public function edit($mobileLanguageId, $mobileLanguageStringId)
    {
        // check permission start
        $languageString = $this->mobileLanguageStringService->get($mobileLanguageStringId);

        $this->handlePermissionWithModel($languageString, Constants::editAbility);

        $dataArr = $this->prepareEditData($mobileLanguageId, $mobileLanguageStringId);

        return renderView(self::editPath, $dataArr);
    }
   
    public function importCSV($languageId, Request $request)
    {
        $csvFile = $this->prepareCsvFile($request);
        $this->mobileLanguageStringService->importCSV($languageId, $csvFile);
        return redirect()->back();
    }
    public function exportJson($languageId)
    {
        $dataArr = $this->mobileLanguageStringService->exportJson($languageId);


        return $dataArr;
    }
    public function exportCSV($languageId)
    {
        $dataArr = $this->mobileLanguageStringService->exportCSV($languageId);


        return $dataArr;
    }


    public function update($mobileLanguageId, UpdateMobileLanguageStringRequest $request, $mobileLanguageStringId)
    {
        $routeParams = [$mobileLanguageId, $mobileLanguageStringId];

        try {
            // Validate the request data
            $validData = $request->validated();

            $this->mobileLanguageStringService->update($mobileLanguageStringId, $validData);

            // Success and Redirect
            return redirectView(self::indexRoute, null, $routeParams);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $routeParams);
        }
    }

    public function destroy($mobileLanguageId, $mobileLanguageStringId)
    {
        try {
            $langString = $this->mobileLanguageStringService->get($mobileLanguageStringId);

            $this->handlePermissionWithModel($langString, Constants::deleteAbility);

            $dataArr = $this->mobileLanguageStringService->delete($mobileLanguageId, $mobileLanguageStringId);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag'], $mobileLanguageId);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $mobileLanguageId);
        }
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

    private function prepareIndexData($languageId, $request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'location_city_id' => $request->input('city_filter') === 'all' ? null : $request->input('city_filter'),
            'order_by' => $request->input('sort_field') ?? 'added_date',
            'order_type' => $request->input('sort_order') ?? 'desc',
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $language = $this->mobileLanguageService->get($languageId);

        $relations = ['mobileLanguage', 'owner', 'editor'];
        $language_strings = MobileLanguageStringWithKeyResource::collection($this->mobileLanguageStringService->getAll($languageId, $relations, null, null, $conds, false, $row));



        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::mobileLanguageString, $this->controlFieldArr());

        // dd($columnAndColumnFilter);

        // prepare for permission
        $keyValueArr = [
            'createMobileLanguageString' => 'create-mobileLanguageString',
        ];

        return [
            'mobile_language' => $language,
            'mobile_language_strings' => $language_strings,
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareCreateData($mobileLanguageId)
    {
        $mobileLanguage = $this->mobileLanguageService->get($mobileLanguageId);

        return [
            'mobile_language' => $mobileLanguage,
        ];
    }

    private function prepareEditData($mobileLanguageId, $mobileLanguageStringId)
    {
        $mobileLanguage = $this->mobileLanguageService->get($mobileLanguageId);
        $mobileLanguageString = $this->mobileLanguageStringService->get($mobileLanguageStringId);

        return [
            'mobile_language' => $mobileLanguage,
            'mobile_language_string' => $mobileLanguageString,
        ];
    }

    private function prepareStatusData($languageString)
    {
        return $languageString->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareCsvFile($request)
    {
        return $request->file(Constants::csvFile);
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
