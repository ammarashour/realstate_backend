<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Financial;

use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Config\ps_constant;
use App\Http\Contracts\Financial\ItemCurrencyServiceInterface;
use App\Http\Controllers\PsController;
use Modules\Core\Http\Requests\Financial\StoreItemCurrencyRequest;
use Modules\Core\Http\Requests\Financial\UpdateItemCurrencyRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Transformers\Backend\Model\Financial\ItemCurrencyWithKeyResource;

class ItemCurrencyController extends PsController
{
    private const parentPath = "currency/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "currency.index";
    private const createRoute = "currency.create";
    private const editRoute = "currency.edit";

    public function __construct(protected ItemCurrencyServiceInterface $currencyService)
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(ItemCurrency::class, Constants::viewAnyAbility);
        
        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }
    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(ItemCurrency::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StoreItemCurrencyRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Iem Currency
            $this->currencyService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function show($currency)
    {
        return redirect()->route('currency.edit', $currency);
    }

    public function edit($id)
    {
        // check permission start
        $currency = $this->currencyService->get($id);
        $this->handlePermissionWithModel($currency, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateItemCurrencyRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->currencyService->update($id, $validatedData);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $itemCurrency = $this->currencyService->get($id);

            $this->handlePermissionWithModel($itemCurrency, Constants::deleteAbility);

            $dataArr = $this->currencyService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        };
    }

    public function statusChange($id)
    {
        try {

            $itemCurrency = $this->currencyService->get($id);

            $this->handlePermissionWithModel($itemCurrency, Constants::editAbility);

            $status = $this->prepareStatusData($itemCurrency);

            $this->currencyService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function defaultChange($id)
    {
        try {
            $itemCurrency = $this->currencyService->get($id);

            $this->handlePermissionWithModel($itemCurrency, Constants::editAbility);
            if($itemCurrency->is_default == Constants::unPublish)
            {
                $status = $this->prepareIsDefaultData($itemCurrency);

                $this->currencyService->defaultChange($id, $status);
            }

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }


    public function import(Request $request)
    {
        $file = $this->prepareFile($request);
        $dataArr = $this->currencyService->import($file);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
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
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate currency data
        $currencies = ItemCurrencyWithKeyResource::collection($this->currencyService->getAll(null, null, null, null, false,  $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::currency, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createItemCurrency' => 'create-itemCurrency',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'currencies' => $currencies,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {
        $currency = $this->currencyService->get($id);

        return[
            "currency" => $currency
        ];
    }

    private function prepareStatusData($itemCurrency)
    {
        return $itemCurrency->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }
    private function prepareIsDefaultData($itemCurrency)
    {
        return $itemCurrency->is_default == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareFile($request)
    {
        return $request->file('file');
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

