<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Localization;

use Illuminate\Http\Request;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Validator;
use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Entities\Localization\VendorLanguageString;
use Modules\Core\Transformers\Backend\Model\Localization\VendorLanguageStringWithKeyResource;
use Modules\Core\Http\Requests\Localization\StoreVendorLanguageStringRequest;
use Modules\Core\Http\Requests\Localization\UpdateVendorLanguageStringRequest;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use Modules\Core\Http\Facades\LanguageFacade;

class VendorLanguageStringController extends PsController
{
    private const parentPath = "vendor_languages_strings/";
    private const indexPath = self::parentPath."Index";
    private const createPath = self::parentPath."Create";
    private const editPath = self::parentPath."Edit";
    private const indexRoute = "vendor_language_string.index";
    private const createRoute = "vendor_language_string.create";
    private const editRoute = "vendor_language_string.edit";

    public function __construct(
    protected VendorLanguageStringServiceInterface $languageStringService, 
    protected CoreFieldServiceInterface $coreFieldFilterSettingService)
    {
        parent::__construct();
    }

    public function index($languageId,Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorLanguageString::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($languageId, $request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create($languageId)
    {
        // check permission start
        $this->handlePermissionWithModel(VendorLanguageString::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData($languageId);

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreVendorLanguageStringRequest $request)
    {
        $routeParams = [$request['language_id']];

        try {
            // Validate the request data
            $validData = $request->validated();

            $this->languageStringService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute, null, $routeParams);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage(), $routeParams);
        }
    }

    public function edit($languageId, $languageStringId)
    {
       // check permission start
       $languageString = $this->languageStringService->get($languageStringId);
       $this->handlePermissionWithModel($languageString, Constants::editAbility);

       $dataArr = $this->prepareEditData($languageId, $languageStringId);

       return renderView(self::editPath, $dataArr);
    }

    public function update($language_id, $id, UpdateVendorLanguageStringRequest $request)
    {
        $routeParams = [$language_id, $id];

        try {
            // Validate the request data
            $validData = $request->validated();

            $this->languageStringService->update($id, $validData);

            // Success and Redirect
            return redirectView(self::indexRoute, null, $routeParams);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $routeParams);
        }
    }

    public function destroy($languageId, $languageStringId)
    {
        try {
            $langString = $this->languageStringService->get($languageStringId);

            $this->handlePermissionWithModel($langString, Constants::deleteAbility);

            $dataArr = $this->languageStringService->delete($languageId, $languageStringId);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag'], $languageId);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $languageId);
        }
    }

    public function importCSV($languageId, Request $request)
    {

        $csvFile = $this->prepareCsvFile($request);
        $this->languageStringService->importCSV($languageId, $csvFile);
        return redirect()->back();
    }

    public function exportJson($languageId)
    {
        $dataArr = $this->languageStringService->exportJson($languageId);

        return $dataArr;
    }

    public function exportCSV($languageId)
    {
        $dataArr = $this->languageStringService->exportCSV($languageId);

        return $dataArr;
    }

    /**
     * @deprecated
     */
    public function getLanguageString(Request $request)
    {

        return $this->languageStringService->getAll(null, null, null, [VendorLanguageString::key => $request->key]);
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

        $language = LanguageFacade::get($languageId);

        $relations = ['owner', 'editor', 'language'];

        $language_strings = VendorLanguageStringWithKeyResource::collection($this->languageStringService->getAll($languageId, $relations, $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorLanguageString, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorLanguageString' => 'create-vendorLanguageString',
            'createLanguageString' => 'create-languageString'
        ];

        return[
            'language' => $language,
            'vendor_language_strings' => $language_strings,
            'showLanguageStringCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }
    private function prepareCreateData($languageId)
    {
        $language = LanguageFacade::get($languageId);
        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getAll(withNoPag: 1, code: Constants::languageString);

        return [
            'language' => $language,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
    }
    private function prepareEditData($languageId, $languageStringId)
    {
        $language = LanguageFacade::get($languageId);

        $languageString = $this->languageStringService->get($languageStringId);

        return [
            'language' => $language,
            'vendor_language_string' => $languageString,
        ];
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
