<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Financial;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\Financial\StorePackageInAppPurchaseRequest;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Requests\Financial\UpdatePackageInAppPurchaseRequest;
use Modules\Core\Http\Services\Financial\PackageInAppPurchaseSettingService;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Transformers\Backend\NoModel\Financial\PackageInAppPurchaseWithKeyResource;
use App\Http\Contracts\Financial\PackageInAppPurchaseServiceInterface;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;

class PackageInAppPurchaseSettingController extends PsController
{
    private const parentPath = "payment/package_in_app_purchase";

    private const indexPath = self::parentPath . "/Index";

    private const createPath = self::parentPath . "/Create";

    private const editPath = self::parentPath . "/Edit";

    private const indexRoute = "package_in_app_purchase.index";

    private const createRoute = "package_in_app_purchase.create";

    private const editRoute = "package_in_app_purchase.edit";

    public function __construct(
        protected PackageInAppPurchaseServiceInterface $packageInAppPurchaseSettingService,
        protected PaymentSettingService $paymentSettingService,
        protected AvailableCurrencyService $availableCurrencyService,
        protected PaymentAttributeServiceInterface $paymentAttributeService,
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function store(StorePackageInAppPurchaseRequest $request)
    {
        try {
            // Save
            $this->packageInAppPurchaseSettingService->save(PackageIAPData: $request);

            //Success and redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function create()
    {
        $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::createPermission, Auth::user()->id);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function edit($id)
    {
        $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::updatePermission, Auth::user()->id);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdatePackageInAppPurchaseRequest $request, $id)
    {
        try {

            $this->packageInAppPurchaseSettingService->update(
                id: $id,
                PackageIAPData: $request,
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), Constants::danger, $id);
        }
    }

    public function destroy($id)
    {
        try {

            $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::deletePermission, Auth::user()->id);

            $dataArr = $this->packageInAppPurchaseSettingService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $conds = [
                'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
                'core_keys_id' => $id
            ];

            $paymentAttr = $this->paymentAttributeService->get(null, $conds);

            $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::updatePermission, Auth::user()->id);

            $status = $this->prepareStatusData($paymentAttr);

            $this->packageInAppPurchaseSettingService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
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
        $conds = [
            'searchterm' => $request->input('search') ?? '',

            'type' => $request->input('type_filter') === 'all' ? null : $request->input('type_filter'),

            'currency_id' => $request->input('currency_filter') === 'all' ? null : $request->input('currency_filter'),

            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'payment_id' => Constants::packageInAppPurchasePaymentId
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate blog data
        $relations = ['core_key'];
        $attributes = [
            Constants::pmtAttrPackageIapTypeCol,
            Constants::pmtAttrPackageIapCountCol,
            Constants::pmtAttrPackageIapPriceCol,
            Constants::pmtAttrPackageIapStatusCol,
            Constants::pmtAttrPackageIapCurrencyCol
        ];
        $inAppPurchases = PackageInAppPurchaseWithKeyResource::collection($this->paymentSettingService->getPaymentInfos($relations, null, null, $conds, false, $row, $attributes));
        $currencies = $this->availableCurrencyService->getAll(null, Constants::publish);

        $types = [
            [
                'id' => Constants::iapTypeAndroid,
                'name' => Constants::iapTypeAndroid,
            ],
            [
                'id' => Constants::iapTypeIOS,
                'name' => Constants::iapTypeIOS,
            ],
        ];

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());
        $columnProps = $columnAndColumnFilter['handlingColumn'];
        $columnFilterOptionProps = $columnAndColumnFilter['handlingFilter'];

        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "inAppPurchases" => $inAppPurchases,
                "countKey" => Constants::pmtAttrPackageIapCountCol,
                "typeKey" => Constants::pmtAttrPackageIapTypeCol,
                "priceKey" => Constants::pmtAttrPackageIapPriceCol,
                "statusKey" => Constants::pmtAttrPackageIapStatusCol,
                "currencyKey" => Constants::pmtAttrPackageIapCurrencyCol,
                "currencies" => $currencies,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'types' => $types,
                'selected_type' => $conds['type'],
                'selected_currency' => $conds['currency_id'],
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "inAppPurchases" => $inAppPurchases,
                "countKey" => Constants::pmtAttrPackageIapCountCol,
                "typeKey" => Constants::pmtAttrPackageIapTypeCol,
                "priceKey" => Constants::pmtAttrPackageIapPriceCol,
                "statusKey" => Constants::pmtAttrPackageIapStatusCol,
                "currencyKey" => Constants::pmtAttrPackageIapCurrencyCol,
                "currencies" => $currencies,
                'search' => $conds['searchterm'],
                'types' => $types,
                'selected_type' => $conds['type'],
                'selected_currency' => $conds['currency_id'],
            ];
        }
        return $dataArr;
    }

    private function prepareCreateData()
    {
        $availableCurrencies = $this->availableCurrencyService->getAll(null,Constants::publish);
        return [
            "availableCurrencies" => $availableCurrencies,
        ];
    }

    private function prepareEditData($id)
    {

        $relations = ['core_key', 'payment_attributes'];
        $inAppPurchase = $this->packageInAppPurchaseSettingService->get($id, $relations);
        $count_attribute = '';
        $price_attribute = '';
        $type_attribute = '';
        $status_attribute = 0;
        $currency_attribute = '';
        foreach ($inAppPurchase['payment_attributes'] as $attribute) {
            if ($attribute['attribute_key'] == Constants::pmtAttrPackageIapCountCol) {
                $count_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrPackageIapPriceCol) {
                $price_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrPackageIapTypeCol) {
                $type_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrPackageIapStatusCol) {
                $status_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrPackageIapCurrencyCol) {
                $currency_attribute = $attribute['attribute_value'];
            }
        }

        $availableCurrencies = $this->availableCurrencyService->getAll(null,Constants::publish);
        return [
            "inAppPurchase" => $inAppPurchase,
            "count_attribute" => $count_attribute,
            "price_attribute" => $price_attribute,
            "type_attribute" => $type_attribute,
            "status_attribute" => $status_attribute,
            "currency_attribute" => $currency_attribute,
            "availableCurrencies" => $availableCurrencies,
        ];
    }


    private function prepareStatusData($paymentAttr)
    {
        return               $paymentAttr->attribute_value == Constants::publish
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
