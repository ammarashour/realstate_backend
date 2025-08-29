<?php

namespace Modules\Payment\Http\Services;

use App\Config\Cache\AppInfoCache;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Payment\Entities\Payment;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Facades\PsCache;
use Modules\Payment\Transformers\Backend\Model\Payment\PaymentWithKeyResource;

class PaymentService extends PsService
{
    protected $paymentTableName, $paymentIdCol, $paymentNameCol, $paymentDescriptionCol, $paymentStatusCol, $paymentInfoIdCol, $paymentInfoPaymentIdCol, $paymentInfoCoreKeysIdCol, $paymentInfoValueCol, $paymentInfoUiTypeIdCol, $paymentInfoShopIdCol, $publish, $unPublish, $offlinePaymentId, $promotionInAppPurchasePaymentId, $packageInAppPurchasePaymentId, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $pmtTableCode, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol, $code, $dropDownUi, $radioUi;

    public function __construct(protected CoreKeyServiceInterface $coreKeyService)
    {
        $this->paymentIdCol = Payment::id;
        $this->paymentNameCol = Payment::name;
        $this->paymentDescriptionCol = Payment::description;
        $this->paymentStatusCol = Payment::status;
        $this->paymentTableName = Payment::tableName;
        $this->paymentAddedDateCol = Payment::addedDate;
        $this->paymentAddedUserIdCol = Payment::addedUserId;


        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->offlinePaymentId = Constants::offlinePaymentId;
        $this->promotionInAppPurchasePaymentId = Constants::promotionInAppPurchasePaymentId;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;


        $this->pmtTableCode = Constants::paymentTableCode;

        $this->dropDownUi = Constants::dropDownUi;
        $this->radioUi = Constants::radioUi;

        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::payment;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $payment = new Payment();
            $payment->id = generateCoreKey($this->pmtTableCode);
            if (isset($request->name) && !empty($request->name))
                $payment->name = $request->name;

            if (isset($request->description) && !empty($request->description))
                $payment->description = $request->description;

            if (isset($request->status))
                $payment->status = $request->status;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $payment->added_user_id = $request->added_user_id;
            else
                $payment->added_user_id = Auth::user()->id;

            $payment->save();

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
            return $payment;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $payment = $this->getPayment($id);

            if (isset($request->name) && !empty($request->name))
                $payment->name = $request->name;

            if (isset($request->description) && !empty($request->description))
                $payment->description = $request->description;

            if (isset($request->status))
                $payment->status = $request->status;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $payment->added_user_id = $request->added_user_id;
            else
                $payment->added_user_id = Auth::user()->id;

            $payment->update();

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
            return $payment;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function makePublishOrUnpublish($id)
    {
        $payment = $this->getPayment($id);

        if ($payment->status == $this->publish) {
            $payment->status = $this->unPublish;
        } else {
            $payment->status = $this->publish;
        }

        $payment->updated_user_id = Auth::user()->id;
        $payment->update();

        PsCache::clear(AppInfoCache::BASE);

    }

    public function makeColumnHideShown($request)
    {
        $hideShowFields = $request->value;
        //        foreach ($hideShowFields as $hideShowField){
        //            $DynamicColumnVisibility = DynamicColumnVisibility::find($hideShowField[$this->screenDisplayUiIdCol]);
        //            $DynamicColumnVisibility->is_show = $hideShowField['hidden'] ? $this->hide : $this->show;
        //            $DynamicColumnVisibility->update();
        //        }
        foreach ($hideShowFields as $hideShowField) {
            $hideShowFieldData[] = [
                $this->screenDisplayUiIdCol => $hideShowField['id'],
                $this->screenDisplayUiIsShowCol => $hideShowField['hidden'] ? $this->hide : $this->show,
            ];
        }
        DynamicColumnVisibility::upsert(
            $hideShowFieldData,
            [$this->screenDisplayUiIdCol],
            [$this->screenDisplayUiIsShowCol]
        );
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->paymentTableName . '.' . $this->paymentNameCol, 'like', '%' . $search . '%');
                $query->orWhere($this->paymentTableName . '.' . $this->paymentDescriptionCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['name']) && $conds['name']) {
            $query->where($this->paymentNameCol, $conds['name']);
        }

        if (isset($conds['description']) && $conds['description']) {
            $query->where($this->paymentDescriptionCol, $conds['description']);
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->where($this->paymentStatusCol, $conds['status']);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where($this->paymentAddedDateCol, $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->paymentAddedUserIdCol, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy($this->paymentIdCol, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getPayments($relations = null, $limit = null, $offset = null, $conds = null, $removeIds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $payments = Payment::when($relations, function ($query, $relations) {
            $query->with($relations);
        })->when($conds, function ($query, $conds) {
            $query = $this->searching($query, $conds);
        })
            ->when($removeIds, function ($query, $removeIds) {
                $query->whereNotIn($this->paymentIdCol, $removeIds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->paymentStatusCol, 'desc')->orderBy($this->paymentNameCol, 'asc');
            });

        if ($pagPerPage) {
            $payments = $payments->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $payments = $payments->get();
        }

        return $payments;
    }

    public function getPayment($id, $relations = null, $conds = null)
    {
        $payment = Payment::where($this->paymentIdCol, $id)
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $payment;
    }

    public function index($request)
    {
        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, Payment::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $relations = ['owner', 'editor'];
        $removeIds = ['payment00009'];
        $payments = PaymentWithKeyResource::collection($this->getPayments($relations, null, null, $conds, $removeIds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showPaymentCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showPaymentCols' => $showPaymentCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'payments' => $payments,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showPaymentCols' => $showPaymentCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'payments' => $payments,
                'search' => $conds['searchterm'],
            ];
        }

        return $dataArr;
    }

    public function create()
    {

        // check permission start
        $checkPermission = $this->checkPermission($this->createAbility, Payment::class, "admin.index");
        // check permission end

        $dataArr = [
            "checkPermission" => $checkPermission,
        ];

        return $dataArr;
    }

    public function edit($id)
    {
        $code = $this->code;
        $payment = $this->getPayment($id);
        $paymentCoreKeys = $this->coreKeyService->getAll(paymentId: $id); // get data payment core key (custom field)

        // check permission start
        $checkPermission = $this->checkPermission($this->editAbility, $payment, "admin.index");
        // check permission end

        $dataArr = [
            "checkPermission" => $checkPermission,
            "payment" => $payment,
            "paymentCoreKeys" => $paymentCoreKeys,

            // no need to show payment core_key
            "offlinePaymentId" => $this->offlinePaymentId,
            "promotionIAPPaymentId" => $this->promotionInAppPurchasePaymentId,
            "packageIAPPaymentId" => $this->packageInAppPurchasePaymentId,
            "vendorSubscriptoinPlanPaymentId" => Constants::vendorSubscriptionPlanPaymentId,
            "flutterwavePaymentId" => Constants::flutterwavePaymentId,
        ];
        return $dataArr;
    }

    public function getCoreFieldFilteredLists($code)
    {
        return CoreField::where($this->coreFieldFilterModuleNameCol, $code)->get();
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;
                $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;

                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showProductCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showProductCols,
        ];
        return $dataArr;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }
}
