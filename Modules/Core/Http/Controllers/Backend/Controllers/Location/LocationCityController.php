<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Location;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
// use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Location\LocationCity;
use App\Http\Controllers\PsController;
use Modules\Core\Http\Requests\StoreLocationCityRequest;
use Modules\Core\Http\Requests\UpdateLocationCityRequest;
use Modules\Core\Imports\LocationCityImport;
use Modules\Core\Transformers\Backend\Model\Location\LocationCityWithKeyResource;

class LocationCityController extends PsController
{
    private const parentPath = 'location_city';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = 'city.index';

    private const createRoute = 'city.create';

    private const editRoute = 'city.edit';

    public function __construct(
        protected LocationCityServiceInterface $cityService,
        protected CoreFieldServiceInterface $coreFieldFilterSetting,
        protected BackendSettingServiceInterface $backendSettingService,
        protected CustomFieldServiceInterface $customizeUiService,
        protected CustomFieldAttributeServiceInterface $customizeUiDetailService,
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(LocationCity::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(LocationCity::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreLocationCityRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            $relationalData = $this->prepareDataCustomFields($request);

            // Save Blog
            $this->cityService->save(locationCityData: $validData, relationalData: $relationalData);

            //Success and redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        $city = $this->cityService->get($id);
        //check permission start
        $this->handlePermissionWithModel($city, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateLocationCityRequest $request, $id)
    {
        try {

            $validData = $request->validated();

            $relationalData = $this->prepareDataCustomFields($request);

            $this->cityService->update(
                id: $id,
                locationCityData: $validData,
                relationalData: $relationalData
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $city = $this->cityService->get($id);

            $this->handlePermissionWithModel($city, Constants::deleteAbility);

            $dataArr = $this->cityService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $city = $this->cityService->get($id);

            $this->handlePermissionWithModel($city, Constants::editAbility);

            $status = $this->prepareStatusData($city);

            $this->cityService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function screenDisplayUiStore(Request $request)
    {

        makeColumnHideShown($request);

        return redirect()->back();
    }

    public function importCSV(Request $request)
    {

        try {
            $locationCityData = $request->file(Constants::csvFile);

            $this->cityService->importCSVFile($locationCityData);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData($request)
    {
        $cityRelations = [];

        $customizeUis = $this->customizeUiService->getAll(moduleName: CustomField::moduleName, uiTypeId: CustomField::uiTypeId);

        $uis = $this->customizeUiDetailService->getAll(coreKeysId: $customizeUis->pluck(CustomFieldAttribute::coreKeysId));

        $customizeUisByModule = $this->customizeUiService->getAll(moduleName: CustomField::moduleName);

        $customizeDetails = $this->customizeUiDetailService->getAll(coreKeysId: $customizeUisByModule->pluck(CustomFieldAttribute::coreKeysId));

        // Search and filter
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'page' => $request->input('page') ?? null
        ];
        if (! empty($cityRelations)) {
            $conds['city_relation'] = $cityRelations;
        }
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $cities = LocationCityWithKeyResource::collection($this->cityService->getAll($cityRelations, null, null, null, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::locationCity, $this->controlFieldArr());
        // dd($columnAndColumnFilter);
        $columnProps = $columnAndColumnFilter['handlingColumn'];
        $columnFilterOptionProps = $columnAndColumnFilter['handlingFilter'];

        // prepare for permission
        $keyValueArr = [
            'createLocationCity' => 'create-locationCity',
        ];

        if ($conds['order_by']) {
            return [
                'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'cities' => $cities,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'uis' => $uis,
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule,
            ];
        } else {
            return [
                'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'cities' => $cities,
                'items' => $cities,
                'search' => $conds['searchterm'],
                'uis' => $uis,
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule,
            ];
        }
    }

    private function prepareCreateData()
    {
        $customizeHeader = [];

        $coreFieldFilterSettings = $this->coreFieldFilterSetting->getAll(Constants::locationCity);

        $customizeHeader = $this->customizeUiService->getAll(isDelete: 0, code: Constants::locationCity, withNoPag: Constants::yes);

        // dd($customizeHeader);
        $customizeDetail = $this->customizeUiDetailService->get();

        $locTableColumns = getAllCoreFields(LocationCity::tableName);

        $backendSettings = $this->backendSettingService->get();

        return [
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'locTableColumns' => $locTableColumns,
            'backendSettings' => $backendSettings,
        ];
    }

    private function prepareEditData($id)
    {
        $customizeHeaders = [];

        $city = $this->cityService->get($id, ['cityRelation']);

        $coreFieldFilterSettings = $this->coreFieldFilterSetting->getAll(Constants::locationCity);

        $customizeHeaders = $this->customizeUiService->getAll(isDelete: 0, code: Constants::locationCity, withNoPag: Constants::yes);

        // dd($customizeHeaders);

        $customizeDetail = $this->customizeUiDetailService->get();

        $backendSettings = $this->backendSettingService->get();

        return [
            'city' => $city,
            'customizeHeaders' => $customizeHeaders,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'backendSettings' => $backendSettings,
        ];
    }

    private function prepareStatusData($city)
    {
        return $city->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareDataCustomFields($request)
    {
        // Retrieve the 'relation' input as an array of strings
        $relationsInput = $request->input('city_relation', []);

        // Retrieve the 'relation' files as an array of files
        $relationsFiles = !empty($request->allFiles()['city_relation']) ? $request->allFiles()['city_relation'] : [];

        // Merge the input and files arrays, preserving keys
        return array_merge($relationsInput, $relationsFiles);
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
