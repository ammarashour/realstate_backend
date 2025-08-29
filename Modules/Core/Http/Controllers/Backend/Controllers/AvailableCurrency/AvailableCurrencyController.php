<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\AvailableCurrency;

use App\Config\ps_constant;
use App\Http\Contracts\AvailableCurrency\AvailableCurrencyServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;
use Modules\Core\Http\Requests\StoreAvailableCurrencyRequest;
use Modules\Core\Http\Requests\UpdateAvailableCurrencyRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Transformers\Backend\Model\AvailableCurrency\AvailableCurrencyWithKeyResource;

class AvailableCurrencyController extends PsController
{
    private const parentPath = 'currency_available/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const indexRoute = 'available_currency.index';

    private const createRoute = 'available_currency.create';

    private const editRoute = 'available_currency.edit';

    public function __construct(
        protected AvailableCurrencyServiceInterface $availableCurrencyService,
        protected CoreFieldFilterSettingService $coreFieldFilterSettingService, )
    {

        parent::__construct();
    }

    public function index(Request $request)
    {

        $this->handlePermissionWithModel(AvailableCurrency::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);

    }

    public function create()
    {

        // check permission start
        $this->handlePermissionWithModel(AvailableCurrency::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);

    }

    public function store(StoreAvailableCurrencyRequest $request)
    {

        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Available Currency
            $this->availableCurrencyService->save(availableCurrencyData : $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }

    }

    public function edit($id)
    {

        $available_currency = $this->availableCurrencyService->get($id);
        //check permission start
        $this->handlePermissionWithModel($available_currency, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);

    }

    public function update(UpdateAvailableCurrencyRequest $request, $id)
    {

        try {
            $validData = $request->validated();

            $this->availableCurrencyService->update(
                id: $id,
                availableCurrencyData: $validData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }

    }

    public function destroy($id)
    {
        try {
            $available_currency = $this->availableCurrencyService->get($id);

            $this->handlePermissionWithModel($available_currency, Constants::deleteAbility);

            $dataArr = $this->availableCurrencyService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }

    public function statusChange($id)
    {
        try {

            $available_currencies = $this->availableCurrencyService->get($id);
            $this->handlePermissionWithModel($available_currencies, Constants::editAbility);
            $status = $this->prepareStatusData($available_currencies);
            $this->availableCurrencyService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }

    public function defaultChange($id){
        try {

            $available_currencies = $this->availableCurrencyService->get($id);

            $this->handlePermissionWithModel($available_currencies, Constants::editAbility);

            $dataArr = $this->availableCurrencyService->defaultChange($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
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

    private function prepareCreateData()
    {

        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getCoreFields(withNoPag: 1, moduleName: Constants::availableCurrency);

        return [
            // "checkPermission" => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareIndexData($request)
    {
        // check permission start
        //$checkPermission = $this->checkPermission(Constants::createAbility,AvailableCurrency::class, "admin.index");

        // search filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $conds['order_by'] = $request->input('sort_field');
        $conds['order_type'] = $request->input('sort_order');

        $available_currencies = AvailableCurrencyWithKeyResource::collection($this->availableCurrencyService->getAll(null, null, null, null, false, $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::availableCurrency, $this->controlFieldArr());

        // changing item arr object with new format
        $changedCurrencyObj = $available_currencies;

        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],

                'currencies' => $changedCurrencyObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $conds['order_type'],
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],

                'currencies' => $changedCurrencyObj,
                'search' => $conds['searchterm'],
            ];
        }

        return $dataArr;

    }

    private function prepareEditData($id)
    {
        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getCoreFields(withNoPag: 1, moduleName: Constants::availableCurrency);

        $available_currency = $this->availableCurrencyService->get($id);

        $dataArr = [
            'currency' => $available_currency,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

        return $dataArr;
    }

    private function prepareStatusData($available_currencies)
    {
        return $available_currencies->status == Constants::publish
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
