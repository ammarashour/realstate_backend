<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Financial;

use App\Config\ps_constant;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Http\Requests\StorePromoteInAppPurchaseRequest;
use Modules\Payment\Http\Requests\UpdatePromoteInAppPurchaseRequest;
use Modules\Payment\Http\Services\PromotionInAppPurchase\PromotionInAppPurchaseSettingService;
use App\Http\Contracts\Financial\PromotionInAppPurchaseSettingServiceInterface;
use Modules\Core\Transformers\Backend\NoModel\Financial\PromotionInAppPurchaseWithKeyResource;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Http\Services\PaymentService;

class PromotionInAppPurchaseSettingController extends PsController
{
    private const parentPath = "payment/promotion_in_app_purchase";
    private const indexPath = self::parentPath . "/Index";
    private const createPath = self::parentPath . "/Create";
    private const editPath = self::parentPath . "/Edit";
    private const indexRoute = "promotion_in_app_purchase.index";
    private const createRoute = "promotion_in_app_purchase.create";
    private const editRoute = "promotion_in_app_purchase.edit";


    public function __construct(protected PromotionInAppPurchaseSettingServiceInterface $promotionInAppPurchaseSettingService,
    protected PaymentSettingService $paymentSettingService,
    protected PaymentAttributeServiceInterface $paymentAttributeService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::promotionInAppPurchaseModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function store(StorePromoteInAppPurchaseRequest $request)
    {
        try {

            // Save
            $this->promotionInAppPurchaseSettingService->save(PromotionIAPData: $request);

            //Success and redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function create()
    {
        $this->handlePermissionWithoutModel(Constants::promotionInAppPurchaseModule, ps_constant::createPermission, Auth::user()->id);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function edit($id)
    {
        $this->handlePermissionWithoutModel(Constants::packageInAppPurchaseModule, ps_constant::updatePermission, Auth::user()->id);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdatePromoteInAppPurchaseRequest $request, $id)
    {
        try {

            $this->promotionInAppPurchaseSettingService->update(
                id: $id,
                PromotionIAPData: $request,
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), Constants::danger, $id);
        }
    }

    public function destroy($id)
    {
        try {

            $this->handlePermissionWithoutModel(Constants::promotionInAppPurchaseModule, ps_constant::deletePermission, Auth::user()->id);

            $dataArr = $this->promotionInAppPurchaseSettingService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id){
        try {

            $conds = [
                'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
                'core_keys_id' => $id
            ];

            $paymentAttr = $this->paymentAttributeService->get(null, $conds);

            $this->handlePermissionWithoutModel(Constants::promotionInAppPurchaseModule, ps_constant::updatePermission, Auth::user()->id);

            $status = $this->prepareStatusData($paymentAttr);

            $this->promotionInAppPurchaseSettingService->setStatus($id, $status);

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
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['type'] = $request->input('type_filter') == 'all'? null  : $request->type_filter;

        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if($request->sort_field)
        {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $conds['payment_id'] = Constants::promotionInAppPurchasePaymentId;
        $relations = ['core_key','statusAttribute'];

        /**
         * attribute_key in payment_attributes table for promotion iap
         * used to convert row to col for attributes (type, status, day) in getPaymentInfos() function
         */
        $attributes = [
            Constants::pmtAttrPromoteIapDayCol,
            Constants::pmtAttrPromoteIapTypeCol,
            Constants::pmtAttrPromoteIapStatusCol,
        ];
        $service = 'PromotionIAP';
        $inAppPurchases = PromotionInAppPurchaseWithKeyResource::collection($this->paymentSettingService->getPaymentInfos($relations, null, null, $conds, false, $row, $attributes,$service));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());
        $columnProps = $columnAndColumnFilter['handlingColumn'];
        $columnFilterOptionProps = $columnAndColumnFilter['handlingFilter'];

        // for pmtAttrPromoteIapTypeCol (Android or IOS)
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

        if($conds['order_by'])
        {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "inAppPurchases" => $inAppPurchases,
                "dayKey" => Constants::pmtAttrPromoteIapDayCol,
                "typeKey" => Constants::pmtAttrPromoteIapTypeCol,
                "statusKey" => Constants::pmtAttrPromoteIapStatusCol,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
                'types' => $types,
                'selected_type'=>$conds['type'] ,
            ];
        }
        else
        {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "inAppPurchases" => $inAppPurchases,
                "dayKey" => Constants::pmtAttrPromoteIapDayCol,
                "typeKey" => Constants::pmtAttrPromoteIapTypeCol,
                "statusKey" => Constants::pmtAttrPromoteIapStatusCol,
                'search'=>$conds['searchterm'] ,
                'types' => $types,
                'selected_type'=>$conds['type'] ,
            ];
        }
        return $dataArr;
    }

    private function prepareCreateData()
    {
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

        $dataArr = [
            "types" => $types,
        ];
        return $dataArr;
    }

    private function prepareEditData($id)
    {
        $day_attribute = '';
        $type_attribute = '';
        $status_attribute = 0;

        $relations = ['core_key', 'payment_attributes'];
        $inAppPurchase = $this->promotionInAppPurchaseSettingService->get($id, $relations);

        foreach($inAppPurchase['payment_attributes'] as $attribute){
            if ($attribute['attribute_key'] == Constants::pmtAttrPromoteIapDayCol) {
                $day_attribute = $attribute['attribute_value'];
            }

            if ($attribute['attribute_key'] == Constants::pmtAttrPromoteIapTypeCol) {
                $type_attribute = $attribute['attribute_value'];
            }

            if ($attribute['attribute_key'] == Constants::pmtAttrPromoteIapStatusCol) {
                $status_attribute = $attribute['attribute_value'];
            }
        }

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

        $dataArr = [
            "inAppPurchase" => $inAppPurchase,
            "day_attribute" => $day_attribute,
            "type_attribute" => $type_attribute,
            "status_attribute" => $status_attribute,
            "types" => $types,
        ];
        return $dataArr;
    }

    private function prepareStatusData($paymentAttr)
    {
        return $paymentAttr->attribute_value == "1"
            ? "0"
            : "1";
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
