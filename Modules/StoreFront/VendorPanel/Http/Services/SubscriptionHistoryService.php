<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Models\User;
use App\Http\Services\PsService;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Entities\SubscriptionBoughtTransaction;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Services\Configuration\CoreKeyService;
use Modules\Core\Http\Services\Financial\PaymentAttributeService;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Services\Vendor\VendorSubscriptionPlanBoughtTransactionService;
use Modules\StoreFront\VendorPanel\Transformers\Backend\NoModel\VendorSubscription\SubscriptionHistoryWithKeyResource;


class SubscriptionHistoryService extends PsService
{
    protected $customUiIsDelCol, $customUiEnableCol, $pmtAttrPackageIapPriceCol, $iapTypeAndroid, $iapTypeIOS, $dangerFlag, $successFlag, $noContentStatusCode, $successStatus, $packageInAppPurchasePaymentId, $paymentInfoIdCol, $paymentInfoCoreKeysIdCol, $paymentInfoPaymentIdCol, $paymentInfoValueCol, $publish, $unPublish, $paymentService, $inAppPurchaseSetting, $coreKeyService, $code, $paymentSettingService, $inAppPurchaseApiRelations, $pmtAttrPackageIapTypeCol, $pmtAttrPackageIapCountCol, $paymentAttributeService, $packageInAppPurchaseApiRelations, $pmtAttrPackageIapStatusCol, $pmtAttrPackageIapCurrencyCol,  $deviceTokenParaApi, $loginUserIdParaApi, $userAccessApiTokenService, $forbiddenStatusCode, $hide, $show, $coreFieldFilterModuleNameCol, $coreFieldFilterIdCol, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $VendorSubscriptionPlanBoughtTransactionService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService, PaymentService $paymentService, CoreKeyService $coreKeyService, PaymentSettingService $paymentSettingService, PaymentAttributeService $paymentAttributeService, VendorSubscriptionPlanBoughtTransactionService $VendorSubscriptionPlanBoughtTransactionService)
    {

        $this->VendorSubscriptionPlanBoughtTransactionService = $VendorSubscriptionPlanBoughtTransactionService;

        $this->paymentInfoIdCol = PaymentInfo::id;
        $this->paymentInfoCoreKeysIdCol = PaymentInfo::coreKeysId;
        $this->paymentInfoPaymentIdCol = PaymentInfo::paymentId;
        $this->paymentInfoValueCol = PaymentInfo::value;

        $this->customUiEnableCol = CustomField::enable;
        $this->customUiIsDelCol = CustomField::isDelete;

        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;
        $this->code = Constants::payment;
        $this->pmtAttrPackageIapTypeCol = Constants::pmtAttrPackageIapTypeCol;
        $this->pmtAttrPackageIapCountCol = Constants::pmtAttrPackageIapCountCol;
        $this->pmtAttrPackageIapPriceCol = Constants::pmtAttrPackageIapPriceCol;
        $this->pmtAttrPackageIapStatusCol = Constants::pmtAttrPackageIapStatusCol;
        $this->pmtAttrPackageIapCurrencyCol = Constants::pmtAttrPackageIapCurrencyCol;

        $this->paymentService = $paymentService;
        $this->coreKeyService = $coreKeyService;
        $this->paymentSettingService = $paymentSettingService;
        $this->paymentAttributeService = $paymentAttributeService;

        $this->inAppPurchaseApiRelations = ['payment', 'core_key', 'payment_attributes'];

        $this->packageInAppPurchaseApiRelations = ['payment', 'core_key', 'payment_info'];

        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;

        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->enable = Constants::enable;
        $this->disable = Constants::disable;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;

        $this->dropDownUi = Constants::dropDownUi;
        $this->textUi = Constants::textUi;
        $this->radioUi = Constants::radioUi;
        $this->checkBoxUi = Constants::checkBoxUi;
        $this->dateTimeUi = Constants::dateTimeUi;
        $this->textAreaUi = Constants::textAreaUi;
        $this->numberUi = Constants::numberUi;
        $this->multiSelectUi = Constants::multiSelectUi;
        $this->imageUi = Constants::imageUi;
        $this->timeOnlyUi = Constants::timeOnlyUi;
        $this->dateOnlyUi = Constants::dateOnlyUi;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;
        $this->coreFieldFilterIdCol = CoreField::id;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->iapTypeAndroid = Constants::iapTypeAndroid;
        $this->iapTypeIOS = Constants::iapTypeIOS;
    }

    public function index($request)
    {
        $code = $this->code;

        // Search and filter

        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['package_id'] = $request->input('package_filter') == 'all' ? null  : $request->package_filter;
        $conds['selected_date'] = $request->input('date_filter') == 'all' ? null  : $request->date_filter;
        $conds['selected_payment_method'] = $request->input('selected_payment_method') == 'all' ? null  : $request->selected_payment_method;

        $conds['order_by'] = null;
        $conds['order_type'] = null;

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        // $conds['payment_id'] = Constants::vendorSubscriptionPlanPaymentId;

        // $durations = [
        //     [
        //         'id' => Constants::vendorSpOneMonth,
        //         'name' => Constants::vendorSpOneMonth,
        //     ],
        //     [
        //         'id' => Constants::vendorSpSixMonths,
        //         'name' => Constants::vendorSpSixMonths,
        //     ],
        //     [
        //         'id' => Constants::vendorSpOneYear,
        //         'name' => Constants::vendorSpOneYear,
        //     ],
        // ];

        // taking for column and columnFilterOption
        // $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        // $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        // $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];


        $relations = ["user", "package"];

        $vendor_id = getVendorIdFromSession();
        // dd($vendor_id);

        $subscriptionHistory = SubscriptionHistoryWithKeyResource::collection($this->getPackageBoughtTransactions($relations, null, null, null, null, $row, null, $vendor_id));
        // dd($subscriptionHistory);
        if ($conds['order_by']) {
            $dataArr = [
                "subs_history" => $subscriptionHistory,
                // 'showCoreAndCustomFieldArr' => $columnProps,
                // 'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "statusKey" => Constants::pmtAttrVendorSpStatusCol,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                // 'durations' => $durations,
            ];
        } else {
            $dataArr = [
                "subs_history" => $subscriptionHistory,
                // 'showCoreAndCustomFieldArr' => $columnProps,
                // 'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                "statusKey" => Constants::pmtAttrVendorSpStatusCol,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                // 'durations' => $durations,
            ];
        }
        return $dataArr;
    }

    public function getPackageBoughtTransactions($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $pagPerPage = null, $searchConds = null, $vendorId = null)
    {

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $transactions = SubscriptionBoughtTransaction::leftJoin(PaymentInfo::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::subscriptionPlanId, '=', PaymentInfo::tableName . '.' . PaymentInfo::id)
            // ->select('psx_package_bought_transactions.*', 'psx_payment_infos.value')
            ->leftjoin(User::tableName, SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::userId, '=', User::tableName . '.' . User::id)
            ->select(SubscriptionBoughtTransaction::tableName . '.*', User::tableName . '.' . User::name . ' as user_name', PaymentInfo::tableName . '.' . PaymentInfo::value, PaymentInfo::tableName . '.' . PaymentInfo::paymentId, PaymentInfo::tableName . '.' . PaymentInfo::coreKeysId)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($status, function ($q, $status) {
                $q->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::status, $status);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($vendorId, function ($q) use ($vendorId) {
                $q->where(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::vendorId, '=', $vendorId);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(SubscriptionBoughtTransaction::tableName . '.' . SubscriptionBoughtTransaction::id, 'desc');
            });
        if ($pagPerPage) {
            $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $transactions = $transactions->get();
        }

        return $transactions;
    }


    // public function getPackageBoughtTransactions($relation = null, $status = null, $limit = null, $offset = null, $conds = null,$pagPerPage = null,$searchConds = null,$vendor_id = null){

    //     $sort = '';
    //     if(isset($conds['order_by'])){
    //         $sort = $conds['order_by'];
    //     }
    //     $transactions = SubscriptionBoughtTransaction::leftJoin(PaymentInfo::tableName, SubscriptionBoughtTransaction::tableName.'.'.SubscriptionBoughtTransaction::subscriptionPlanId, '=', PaymentInfo::tableName.'.'.PaymentInfo::id)
    //                             ->leftjoin(User::tableName, SubscriptionBoughtTransaction::tableName.'.'.SubscriptionBoughtTransaction::userId, '=', User::tableName.'.'.User::id)
    //                             ->leftJoin(Vendor::tableName, User::tableName.'.'.User::id, '=', Vendor::tableName.'.'.Vendor::ownerUserId)
    //                             ->select(SubscriptionBoughtTransaction::tableName.'.*', User::tableName.'.'.User::name.' as user_name', PaymentInfo::tableName.'.'.PaymentInfo::value, PaymentInfo::tableName.'.'.PaymentInfo::paymentId, PaymentInfo::tableName.'.'.PaymentInfo::coreKeysId)
    //                                     ->when($relation, function ($q, $relation){
    //                                         $q->with($relation);
    //                                     })
    //                                     ->when($status, function ($q, $status){
    //                                         $q->where(SubscriptionBoughtTransaction::tableName.'.'.SubscriptionBoughtTransaction::status, $status);
    //                                     })
    //                                     ->when($limit, function ($query, $limit) {
    //                                         $query->limit($limit);
    //                                     })
    //                                     ->when($offset, function ($query, $offset) {
    //                                         $query->offset($offset);
    //                                     })
    //                                     ->when($conds, function ($query, $conds) {
    //                                         $query->where($conds);
    //                                     })
    //                                     ->when(empty($sort), function ($query, $conds){
    //                                         $query->orderBy(SubscriptionBoughtTransaction::tableName.'.'.SubscriptionBoughtTransaction::transactionId, 'desc');
    //                                     })
    //                                     ->where(SubscriptionBoughtTransaction::tableName.'.'.SubscriptionBoughtTransaction::vendorId, $vendor_id);
    //                                     if ($pagPerPage){
    //                                     $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();

    //                                     } else{
    //                                         $transactions = $transactions->get();
    //                                     }
    //     return $transactions;
    // }


    // public function takingForColumnAndFilterOption()
    // {

    //     // for table
    //     $hideShowCoreFieldForColumnArr = [];
    //     $hideShowCustomFieldForColumnArr = [];

    //     $showUserCols = [];

    //     // for eye
    //     $hideShowFieldForColumnFilterArr = [];

    //     // for control
    //     $controlFieldArr = [];
    //     $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action", false, 0);
    //     array_push($controlFieldArr, $controlFieldObj);


    //     $code = $this->code;
    //     $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
    //     $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
    //     $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

    //     foreach ($hideShowForCoreAndCustomFields as $showFields) {
    //         if ($showFields->coreField !== null) {

    //             $label = $showFields->coreField->label_name;
    //             $field = $showFields->coreField->field_name;
    //             $colName = $showFields->coreField->field_name;
    //             $type = $showFields->coreField->data_type;
    //             $isShowSorting = $showFields->coreField->is_show_sorting;
    //             $ordering = $showFields->coreField->ordering;

    //             $cols = $colName;
    //             $id = $showFields->id;
    //             $hidden = $showFields->is_show ? false : true;
    //             $moduleName = $showFields->coreField->module_name;
    //             $keyId = $showFields->coreField->id;

    //             $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type,$isShowSorting, $ordering);
    //             $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

    //             array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
    //             array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
    //             array_push($showUserCols, $cols);
    //         }
    //         if ($showFields->customizeField !== null) {

    //             $label = $showFields->customizeField->name;
    //             $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
    //             if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
    //                 $field = $showFields->customizeField->core_keys_id . "@@name";
    //             } else {
    //                 $field = $showFields->customizeField->core_keys_id;
    //             }
    //             $type = $showFields->customizeField->data_type;
    //             $isShowSorting = $showFields->customizeField->is_show_sorting;
    //             $ordering = $showFields->customizeField->ordering;

    //             $id = $showFields->id;
    //             $hidden = $showFields->is_show ? false : true;
    //             $moduleName = $showFields->customizeField->module_name;
    //             $keyId = $showFields->customizeField->core_keys_id;

    //             $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type ,$isShowSorting, $ordering);
    //             $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

    //             array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
    //             array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
    //         }
    //     }

    //     // for columns props
    //     $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
    //     $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

    //     // for eye
    //     $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

    //     $dataArr = [
    //         "arrForColumnProps" => $sortedColumnArr,
    //         "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
    //         "showCoreField" => $showUserCols,
    //     ];
    //     return $dataArr;
    // }

    // public function hiddenShownForCoreAndCustomField($isShown, $code)
    // {
    //     $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
    //         $q->where($this->customUiEnableCol, $this->enable)->where($this->customUiIsDelCol, $this->unDelete);
    //     }, 'coreField'=> function($q){
    //         $q->where($this->coreFieldFilterModuleNameCol, $this->code);
    //     }])
    //     ->where($this->coreFieldFilterModuleNameCol, $code)
    //         ->where($this->screenDisplayUiIsShowCol, $isShown)
    //         ->get();
    //     return $hiddenShownForFields;
    // }

    // public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    // {
    //     $hideShowCoreAndCustomFieldObj = new \stdClass();
    //     $hideShowCoreAndCustomFieldObj->label = $label;
    //     $hideShowCoreAndCustomFieldObj->field = $field;
    //     $hideShowCoreAndCustomFieldObj->type = $type;
    //     $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
    //     $hideShowCoreAndCustomFieldObj->ordering = $ordering;

    //     if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
    //         $hideShowCoreAndCustomFieldObj->action = 'Action';
    //     }

    //     return $hideShowCoreAndCustomFieldObj;
    // }

    // public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    // {
    //     $hideShowCoreAndCustomFieldObj = new \stdClass();
    //     $hideShowCoreAndCustomFieldObj->id = $id;
    //     $hideShowCoreAndCustomFieldObj->label = $label;
    //     $hideShowCoreAndCustomFieldObj->key = $field;
    //     $hideShowCoreAndCustomFieldObj->hidden = $hidden;
    //     $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
    //     $hideShowCoreAndCustomFieldObj->key_id = $keyId;

    //     return $hideShowCoreAndCustomFieldObj;
    // }

    // From api
    // public function indexFromApi($request)
    // {
    //     // $data = file_get_contents(public_path("json/vendor-subscription-plan.json"));
    //     // return responseDataApi($data);

    //     /// check permission start
    //     $deviceToken = $request->header($this->deviceTokenParaApi);
    //     $userId = $request->login_user_id;

    //     // user token layer permission start
    //     $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
    //     // user token layer permission end

    //     /// check permission end

    //     if (empty($userAccessApiToken)){
    //         $msg = __('payment__api_no_permission',[],$request->language_symbol);
    //         return responseMsgApi($msg, $this->forbiddenStatusCode);
    //     }else{
    //         $offset = $request->offset;
    //         $limit = $request->limit;

    //         $packageInAppPurchaseApiRelations = $this->packageInAppPurchaseApiRelations;
    //         $conds['payment_id'] = Constants::vendorSubscriptionPlanPaymentId;
    //         $conds['status'] = 1;
    //         $attributes = [
    //             Constants::pmtAttrVendorSpDurationCol,
    //             Constants::pmtAttrVendorSpSalePriceCol,
    //             Constants::pmtAttrVendorSpDiscountPriceCol,
    //             Constants::pmtAttrVendorSpCurrencyCol,
    //             Constants::pmtAttrVendorSpIsMostPopularPlanCol,
    //             Constants::pmtAttrVendorSpStatusCol,
    //         ];
    //         $packageIapPayments = VendorSubscriptionPlanSettingApiResource::collection($this->paymentSettingService->getPaymentInfos($packageInAppPurchaseApiRelations, $limit, $offset, $conds, true, null, $attributes));

    //         if ($offset > 0 && $packageIapPayments->isEmpty()) {
    //             // no paginate data
    //             $data = [];
    //             return responseDataApi($data);

    //         } else if($packageIapPayments->isEmpty()) {
    //             // no data db
    //             return responseMsgApi(__('payment__api_no_data'), $this->noContentStatusCode, $this->successStatus);
    //         } else {
    //             return responseDataApi($packageIapPayments);
    //         }
    //     }
    // }

    // public function makePublishOrUnpublish($id){
    //     // update payment attributes table For Status Col
    //     $conds['attribute_key'] = Constants::pmtAttrVendorSpStatusCol;
    //     $conds['core_keys_id'] = $id;
    //     $paymentAttributeStatus = $this->paymentAttributeService->getPaymentAttribute(null, $conds);
    //     if($paymentAttributeStatus){
    //         $paymentAttributeStatus->payment_id = Constants::vendorSubscriptionPlanPaymentId;
    //         $paymentAttributeStatus->core_keys_id = $id;
    //         $paymentAttributeStatus->attribute_key = Constants::pmtAttrVendorSpStatusCol;
    //         $paymentAttributeStatus->attribute_value = $paymentAttributeStatus->attribute_value=="1"?'0':'1';
    //         $this->paymentAttributeService->update($paymentAttributeStatus);
    //     }else{
    //         $paymentAttributeStatus = new PaymentAttribute();
    //         $paymentAttributeStatus->payment_id = Constants::vendorSubscriptionPlanPaymentId;
    //         $paymentAttributeStatus->core_keys_id = $id;
    //         $paymentAttributeStatus->attribute_key = Constants::pmtAttrVendorSpStatusCol;
    //         $paymentAttributeStatus->attribute_value = 1;
    //         $this->paymentAttributeService->store($paymentAttributeStatus);
    //     }
    //     $dataArr = [
    //         'msg' => __('core__be_status_updated'),
    //         'flag' => $this->successFlag,
    //     ];
    //     return $dataArr;
    // }

    // public function handleIsMostPopularPlan($id){
    //     // update payment attributes table For Status Col
    //     $conds['attribute_key'] = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
    //     $conds['core_keys_id'] = $id;
    //     $paymentAttributeStatus = $this->paymentAttributeService->getPaymentAttribute(null, $conds);
    //     if($paymentAttributeStatus){
    //         $paymentAttributeStatus->payment_id = Constants::vendorSubscriptionPlanPaymentId;
    //         $paymentAttributeStatus->core_keys_id = $id;
    //         $paymentAttributeStatus->attribute_key = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
    //         $paymentAttributeStatus->attribute_value = $paymentAttributeStatus->attribute_value=="1"?'0':'1';
    //         $this->paymentAttributeService->update($paymentAttributeStatus);
    //     }else{
    //         $paymentAttributeStatus = new PaymentAttribute();
    //         $paymentAttributeStatus->payment_id = Constants::vendorSubscriptionPlanPaymentId;
    //         $paymentAttributeStatus->core_keys_id = $id;
    //         $paymentAttributeStatus->attribute_key = Constants::pmtAttrVendorSpIsMostPopularPlanCol;
    //         $paymentAttributeStatus->attribute_value = 1;
    //         $this->paymentAttributeService->store($paymentAttributeStatus);
    //     }
    //     $dataArr = [
    //         'msg' => __('core__be_status_updated'),
    //         'flag' => $this->successFlag,
    //     ];
    //     return $dataArr;
    // }
}
