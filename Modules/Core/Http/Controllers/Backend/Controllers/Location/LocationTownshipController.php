<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Location;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Http\Contracts\Location\LocationTownshipServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Http\Requests\StoreLocationTownshipRequest;
use Modules\Core\Http\Requests\UpdateLocationTownshipRequest;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Modules\Core\Imports\LocationTownshipImport;
use Modules\Core\Transformers\Backend\Model\Location\LocationTownshipWithKeyResource;

class LocationTownshipController extends PsController
{
    private const parentPath = 'township';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = 'township.index';

    private const createRoute = 'township.create';

    private const editRoute = 'township.edit';

    public function __construct(
        protected LocationTownshipServiceInterface $townshipService,
        protected CoreFieldService $coreFieldFilterSettingService,
        protected LocationCityServiceInterface $locationCityService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected CustomFieldServiceInterface $customizeUiService,
        protected CustomFieldAttributeServiceInterface $customizeUiDetailService,
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithModel(LocationTownship::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $this->handlePermissionWithModel(LocationTownship::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreLocationTownshipRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            $this->townshipService->save($validData);

            //Success and redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        $township = $this->townshipService->get($id);
        //check permission start
        $this->handlePermissionWithModel($township, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateLocationTownshipRequest $request, $id)
    {
        try {
            $validData = $request->validated();

            $this->townshipService->update(
                id: $id,
                townshipData: $validData,
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $township = $this->townshipService->get($id);

            $this->handlePermissionWithModel($township, Constants::deleteAbility);

            $dataArr = $this->townshipService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {

        try {

            $city = $this->townshipService->get($id);

            $this->handlePermissionWithModel($city, Constants::editAbility);

            $status = $this->prepareStatusData($city);

            $this->townshipService->setStatus($id, $status);

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
            $locationTownshipData = $request->file(Constants::csvFile);

            $this->townshipService->importCSVFile($locationTownshipData);

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

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['location_city_id'] = $request->input('city_filter') == 'all' ? null : $request->city_filter;
        $conds['page'] = $request->input('page') ?? null;

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $customizeUisByModule = $this->customizeUiService->getAll(moduleName: CustomField::moduleName);

        $customizeDetails = $this->customizeUiDetailService->getAll(coreKeysId: $customizeUisByModule);

        $townshipRelation = ['location_city', 'owner', 'editor'];
        // dd($this->townshipService->getAll($townshipRelation, null, null, null, $conds, false, $row));
        $locationTownships = LocationTownshipWithKeyResource::collection($this->townshipService->getAll($townshipRelation, null, null, null, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::locationTownship, $this->controlFieldArr());
        $columnProps = $columnAndColumnFilter['handlingColumn'];
        $columnFilterOptionProps = $columnAndColumnFilter['handlingFilter'];

        // changing item arr object with new format
        $changedProductObj = $locationTownships;
        $selected_City = $this->locationCityService->get($conds['location_city_id']);
        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'townships' => $changedProductObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedCity' => $selected_City ? $selected_City : '',
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule,
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'townships' => $changedProductObj,
                'search' => $conds['searchterm'],
                'selectedCity' => $selected_City ? $selected_City : '',
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule,
            ];
        }

        return $dataArr;
    }

    private function prepareCreateData()
    {
        $cities = $this->locationCityService->getAll(null, Constants::publish);
        $backendSettings = $this->backendSettingService->get();

        return [
            'cities' => $cities,
            'backendSettings' => $backendSettings,
        ];
    }

    private function prepareEditData($id)
    {
        $township = $this->townshipService->get($id);

        $cities = $this->locationCityService->getAll(null, Constants::publish);
        $backendSettings = $this->backendSettingService->get();

        return [
            'township' => $township,
            'cities' => $cities,
            'backendSettings' => $backendSettings,
        ];
    }

    private function prepareStatusData($city)
    {
        return $city->status == Constants::publish
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
