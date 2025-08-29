<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\PaymentInfoCache;
use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Http\Requests\Vendor\StoreVendorSubscriptionPlanRequest;
use Modules\Core\Http\Requests\Vendor\UpdateVendorSubscriptionPlanRequest;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\Vendor\VendorSubscriptionPlanSettingService;
use Modules\Core\Transformers\Backend\NoModel\Vendor\VendorSubscriptionPlanWithKeyResource;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use Modules\Core\Http\Facades\PsCache;
use Modules\Payment\Transformers\Backend\NoModel\VendorSubscriptionPlan\VendorSubscriptionPlanWithKeyResource as VendorSubscriptionPlanVendorSubscriptionPlanWithKeyResource;

class VendorSubscriptionPlanSettingController extends PsController
{
    private const parentPath = 'payment/vendor_subscription_plan';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = 'vendor_subscription_plan.index';

    private const createRoute = 'vendor_subscription_plan.create';

    private const editRoute = 'vendor_subscription_plan.edit';

    public function __construct(protected VendorSubscriptionPlanSettingService $vendorSubscriptionPlanSettingService,
        protected PaymentSettingService $paymentSettingService,
        protected AvailableCurrencyService $availableCurrencyService,
        protected PaymentAttributeServiceInterface $paymentAttributeService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::vendorSubscriptionPlanModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function store(StoreVendorSubscriptionPlanRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save
            $this->vendorSubscriptionPlanSettingService->save(vendorSubscriptionPlanData: $validData);

            //Success and redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function create()
    {
        $this->handlePermissionWithoutModel(Constants::vendorSubscriptionPlanModule, ps_constant::createPermission, Auth::user()->id);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function edit($id)
    {
        $this->handlePermissionWithoutModel(Constants::vendorSubscriptionPlanModule, ps_constant::updatePermission, Auth::user()->id);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorSubscriptionPlanRequest $request, $id)
    {
        try {

            $this->vendorSubscriptionPlanSettingService->update(
                id: $id,
                vendorSubscriptionPlanData: $request,
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), Constants::danger, $id);
        }
    }

    public function destroy($id)
    {
        try {

            $this->handlePermissionWithoutModel(Constants::vendorSubscriptionPlanModule, ps_constant::deletePermission, Auth::user()->id);

            $dataArr = $this->vendorSubscriptionPlanSettingService->delete($id);

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
                'core_keys_id' => $id,
            ];

            $paymentAttr = $this->paymentAttributeService->get(null, $conds);

            $this->handlePermissionWithoutModel(Constants::vendorSubscriptionPlanModule, ps_constant::updatePermission, Auth::user()->id);

            $status = $this->prepareStatusData($paymentAttr);

            $this->vendorSubscriptionPlanSettingService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function handleIsMostPopularPlan($id)
    {
        $dataArr = $this->prepareMostPopularPlanData($id);

        PsCache::clear(AppInfoCache::BASE);
        PsCache::clear(PaymentInfoCache::BASE);

        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
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
        $conds['duration'] = $request->input('type_filter') == 'all' ? null : $request->type_filter;
        $conds['currency_id'] = $request->input('currency_filter') == 'all' ? null : $request->currency_filter;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        $conds['order_by'] = $request->input('sort_field');
        $conds['order_type'] = $request->input('sort_order');


        $conds['payment_id'] = Constants::vendorSubscriptionPlanPaymentId;
        $relations = ['core_key'];
        $attributes = [
            Constants::pmtAttrVendorSpDurationCol,
            Constants::pmtAttrVendorSpSalePriceCol,
            Constants::pmtAttrVendorSpDiscountPriceCol,
            Constants::pmtAttrVendorSpCurrencyCol,
            Constants::pmtAttrVendorSpIsMostPopularPlanCol,
            Constants::pmtAttrVendorSpStatusCol,
        ];
        $vendorSubscriptionPlans = VendorSubscriptionPlanVendorSubscriptionPlanWithKeyResource::collection($this->paymentSettingService->getPaymentInfos($relations, null, null, $conds, false, $row, $attributes));
        $currencies = $this->availableCurrencyService->getAll(null, Constants::publish);

        $durations = [
            [
                'id' => Constants::vendorSpOneMonth,
                'name' => Constants::vendorSpOneMonth,
            ],
            [
                'id' => Constants::vendorSpSixMonths,
                'name' => Constants::vendorSpSixMonths,
            ],
            [
                'id' => Constants::vendorSpOneYear,
                'name' => Constants::vendorSpOneYear,
            ],
        ];

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());
        $columnProps = $columnAndColumnFilter['handlingColumn'];
        $columnFilterOptionProps = $columnAndColumnFilter['handlingFilter'];

        // prepare for permission
        $keyValueArr = [
            'createPayment' => 'create-payment',
        ];


        return [
            'showCoreAndCustomFieldArr' => $columnProps,
            'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            'vendorSubscriptionPlans' => $vendorSubscriptionPlans,
            'durationKey' => Constants::pmtAttrVendorSpDurationCol,
            'salePriceKey' => Constants::pmtAttrVendorSpSalePriceCol,
            'discountPriceKey' => Constants::pmtAttrVendorSpDiscountPriceCol,
            'currencyKey' => Constants::pmtAttrVendorSpCurrencyCol,
            'isMostPopularPlanKey' => Constants::pmtAttrVendorSpIsMostPopularPlanCol,
            'statusKey' => Constants::pmtAttrVendorSpStatusCol,
            'currencies' => $currencies,
            'sort_field' => $conds['order_by'],
            'sort_order' => $request->input('sort_order'),
            'search' => $conds['searchterm'],
            'durations' => $durations,
            'selected_duration' => $conds['duration'],
            'selected_currency' => $conds['currency_id'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareCreateData()
    {
        $availableCurrencies = $this->availableCurrencyService->getAll(null, Constants::publish);

        return [
            'availableCurrencies' => $availableCurrencies,
        ];
    }

    private function prepareEditData($id)
    {
        $relations = ['core_key', 'payment_attributes'];
        $vendorSubscriptionPlan = $this->vendorSubscriptionPlanSettingService->get($id, $relations);
        $duration_attribute = '';
        $sale_price_attribute = '';
        $discount_price_attribute = '';
        $is_most_popular_plan_attribute = 0;
        $status_attribute = 0;
        $currency_attribute = '';

        foreach ($vendorSubscriptionPlan['payment_attributes'] as $attribute) {
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpDurationCol) {
                $duration_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpSalePriceCol) {
                $sale_price_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpDiscountPriceCol) {
                $discount_price_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpStatusCol) {
                $status_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpCurrencyCol) {
                $currency_attribute = $attribute['attribute_value'];
            }
            if ($attribute['attribute_key'] == Constants::pmtAttrVendorSpIsMostPopularPlanCol) {
                $is_most_popular_plan_attribute = $attribute['attribute_value'];
            }
        }

        $availableCurrencies = $this->availableCurrencyService->getAll(null, Constants::publish);

        return [
            'vendorSubscriptionPlan' => $vendorSubscriptionPlan,
            'duration_attribute' => $duration_attribute,
            'sale_price_attribute' => $sale_price_attribute,
            'discount_price_attribute' => $discount_price_attribute,
            'status_attribute' => $status_attribute,
            'is_most_popular_plan_attribute' => $is_most_popular_plan_attribute,
            'currency_attribute' => $currency_attribute,
            'availableCurrencies' => $availableCurrencies,
        ];
    }

    private function prepareStatusData($paymentAttr)
    {
        return $paymentAttr->attribute_value == Constants::publish
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

    private function prepareMostPopularPlanData($id)
    {
        // update payment attributes table For Status Col
        $conds['attribute_key'] = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
        $conds['core_keys_id'] = $id;
        $paymentAttributeStatus = $this->paymentAttributeService->get(null, $conds);

        if ($paymentAttributeStatus) {
            $paymentAttributeStatusArray = [
                'payment_id' => Constants::vendorSubscriptionPlanPaymentId,
                'core_keys_id' => $id,
                'attribute_key' => Constants::pmtAttrVendorSpIsMostPopularPlanCol,
                'attribute_value' => $paymentAttributeStatus->attribute_value == 1 ? 0 : 1,
            ];
            $this->paymentAttributeService->update($paymentAttributeStatus->id, $paymentAttributeStatusArray);
        } else {
            $paymentAttributeStatus = new PaymentAttribute();
            $paymentAttributeStatus->payment_id = Constants::vendorSubscriptionPlanPaymentId;
            $paymentAttributeStatus->core_keys_id = $id;
            $paymentAttributeStatus->attribute_key = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
            $paymentAttributeStatus->attribute_value = 1;
            $this->paymentAttributeService->save($paymentAttributeStatus);
        }
        $dataArr = [
            'msg' => __('core__be_status_updated'),
            'flag' => constants::success,
        ];

        return $dataArr;
    }
}
