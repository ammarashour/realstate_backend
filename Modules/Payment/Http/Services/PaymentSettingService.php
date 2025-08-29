<?php

namespace Modules\Payment\Http\Services;

use App\Config\Cache\PaymentInfoCache;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Transformers\Backend\NoModel\PaymentSetting\PaymentSettingWithKeyResource;
use PHPUnit\TextUI\XmlConfiguration\Constant;

class PaymentSettingService extends PsService
{
    protected $offlinePaymentId;

    protected $promotionInAppPurchasePaymentId;

    protected $packageInAppPurchasePaymentId;

    protected $paymentInfoIdCol;

    protected $paymentInfoCoreKeysIdCol;

    protected $paymentInfoPaymentIdCol;

    protected $paymentInfoValueCol;

    protected $publish;

    protected $unPublish;

    protected $paymentService;

    protected $avalCurStatusCol;

    protected $availableCurrencyService;

    protected $default;

    protected $viewAnyAbility;

    protected $code;

    protected $hide;

    protected $show;

    protected $coreFieldFilterModuleNameCol;

    protected $coreFieldFilterIdCol;

    protected $screenDisplayUiKeyCol;

    protected $screenDisplayUiIdCol;

    protected $screenDisplayUiIsShowCol;

    public function __construct(PaymentService $paymentService, AvailableCurrencyService $availableCurrencyService)
    {
        $this->paymentInfoIdCol = PaymentInfo::id;
        $this->paymentInfoTableName = PaymentInfo::tableName;

        $this->paymentInfoCoreKeysIdCol = PaymentInfo::coreKeysId;
        $this->paymentInfoPaymentIdCol = PaymentInfo::paymentId;
        $this->paymentInfoValueCol = PaymentInfo::value;
        $this->avalCurStatusCol = AvailableCurrency::status;
        $this->avalCurIsDefaultCol = AvailableCurrency::isDefault;

        $this->coreKeyTableName = CoreKey::tableName;
        $this->coreKeyCoreKeysIdCol = CoreKey::coreKeysId;
        $this->coreKeyNameCol = CoreKey::name;
        $this->coreKeyDescriptionCol = CoreKey::description;

        $this->paymentAttributeTableName = PaymentAttribute::tableName;
        $this->paymentAttributeAttributeValueCol = PaymentAttribute::attributeValue;
        $this->paymentAttributeCoreKeysIdCol = PaymentAttribute::coreKeysId;

        $this->publish = Constants::publish;
        $this->default = Constants::default;
        $this->unPublish = Constants::unPublish;
        $this->offlinePaymentId = Constants::offlinePaymentId;
        $this->promotionInAppPurchasePaymentId = Constants::promotionInAppPurchasePaymentId;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;

        $this->customUiEnableCol = CustomField::enable;
        $this->customUiIsDelCol = CustomField::isDelete;

        $this->paymentService = $paymentService;
        $this->availableCurrencyService = $availableCurrencyService;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->enable = Constants::enable;
        $this->disable = Constants::disable;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->code = Constants::payment;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;
        $this->coreFieldFilterIdCol = CoreField::id;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

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
    }

    public function index($request)
    {
        $removeIds = [$this->offlinePaymentId, $this->promotionInAppPurchasePaymentId, $this->packageInAppPurchasePaymentId, Constants::vendorSubscriptionPlanPaymentId, 'payment00009'];
        $payments = PaymentSettingWithKeyResource::collection($this->paymentService->getPayments(['payment_infos'], null, null, null, $removeIds, true));
        $availableCurrencies = $this->availableCurrencyService->getAll(status: $this->publish, noPagination: Constants::yes);

        $conds1[$this->avalCurIsDefaultCol] = $this->default;
        $defaultCurrency = $this->availableCurrencyService->get(conds: $conds1);

        if (empty($defaultCurrency)) {
            $defaultCurrencyId = '';
        } else {
            $defaultCurrencyId = $defaultCurrency->id;
        }

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        $paypalPaymentId = Constants::paypalPaymentId;
        $stripePaymentId = Constants::stripePaymentId;
        $razorPaymentId = Constants::razorPaymentId;
        $paystackPaymentId = Constants::paystackPaymentId;
        $flutterwavePaymentId = Constants::flutterwavePaymentId;
        $dataArr = [
            'showCoreAndCustomFieldArr' => $columnProps,
            'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            'payments' => $payments,
            'availableCurrencies' => $availableCurrencies,
            'defaultCurrencyId' => $defaultCurrencyId,
            'paypalPaymentId' => $paypalPaymentId,
            'stripePaymentId' => $stripePaymentId,
            'razorPaymentId' => $razorPaymentId,
            'paystackPaymentId' => $paystackPaymentId,
            'flutterwavePaymentId' => $flutterwavePaymentId,
        ];

        return $dataArr;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $paymentInfo = new PaymentInfo();

            if (isset($request->core_keys_id) && ! empty($request->core_keys_id)) {
                $paymentInfo->core_keys_id = $request->core_keys_id;
            }

            if (isset($request->payment_id) && ! empty($request->payment_id)) {
                $paymentInfo->payment_id = $request->payment_id;
            }

            if (isset($request->value) && ! empty($request->value)) {
                $paymentInfo->value = $request->value;
            }

            if (isset($request->added_user_id) && ! empty($request->added_user_id)) {
                $paymentInfo->added_user_id = $request->added_user_id;
            } else {
                $paymentInfo->added_user_id = Auth::user()->id;
            }

            $paymentInfo->save();

            DB::commit();

            return $paymentInfo;
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $paymentInfo = $this->getPaymentInfo($id);

            if (isset($request->core_keys_id) && ! empty($request->core_keys_id)) {
                $paymentInfo->core_keys_id = $request->core_keys_id;
            }

            if (isset($request->payment_id) && ! empty($request->payment_id)) {
                $paymentInfo->payment_id = $request->payment_id;
            }

            if (isset($request->value) && ! empty($request->value)) {
                $paymentInfo->value = $request->value;
            }

            if (isset($request->updated_user_id) && ! empty($request->updated_user_id)) {
                $paymentInfo->updated_user_id = $request->updated_user_id;
            } else {
                $paymentInfo->updated_user_id = Auth::user()->id;
            }

            $paymentInfo->update();

            DB::commit();

            return $paymentInfo;
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['error' => $e->getMessage()];
        }
    }

    public function getSqlForCustomField($attributes)
    {
        $sql = '';
        foreach ($attributes as $attribute) {
            $sql .= "max(case when psx_payment_attributes.attribute_key = '$attribute' then ";
            if ($attribute == 'price' || $attribute == 'count' || $attribute == 'days') {
                // $sql .= "Cast(psx_payment_attributes.attribute_value AS INT) end) as '$attribute' ,";
                $sql .= "psx_payment_attributes.attribute_value end) as '$attribute' ,";
            } else {

                $sql .= "psx_payment_attributes.attribute_value end) as '$attribute' ,";
            }
        }
        if ($sql) {
            $sql = rtrim($sql, ',');
        }

        return $sql;
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            if ($search) {

                $query->join($this->coreKeyTableName, $this->coreKeyTableName . '.' . $this->coreKeyCoreKeysIdCol, '=', $this->paymentInfoTableName . '.' . $this->paymentInfoCoreKeysIdCol);
            }

            $query->where(function ($query) use ($search) {
                $query->where($this->paymentInfoValueCol, 'like', '%' . $search . '%');
                $query->orWhere($this->coreKeyTableName . '.' . $this->coreKeyNameCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['payment_id']) && $conds['payment_id']) {
            $query->where($this->paymentInfoTableName . '.' . $this->paymentInfoPaymentIdCol, $conds['payment_id']);
        }

        if (isset($conds['core_keys_id']) && $conds['core_keys_id']) {
            $query->where($this->paymentInfoTableName . '.' . $this->paymentInfoCoreKeysIdCol, $conds['core_keys_id']);
        }

        if (isset($conds['value']) && $conds['value']) {
            $query->where('value', $conds['value']);
        }

        if (isset($conds['type']) && $conds['type']) {
            $query->having('type', $conds['type']);
        }

        if (isset($conds['day']) && $conds['day']) {
            $query->having('day', $conds['day']);
        }

        if (isset($conds['currency_id']) && $conds['currency_id']) {
            $query->having('currency_id', $conds['currency_id']);
        }

        if (isset($conds['post_count']) && $conds['post_count']) {
            $query->having('post_count', $conds['post_count']);
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->having('status', $conds['status']);
        }

        if (isset($conds['count']) && $conds['count']) {
            $query->having('count', $conds['count']);
        }

        if (isset($conds['shop_id']) && $conds['shop_id']) {
            $query->where('shop_id', $conds['shop_id']);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where('added_date', $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where('added_user_id', $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'title') {
                $query->orderBy('payment_title', $conds['order_type']);
            } elseif ($conds['order_by'] == 'description') {
                $query->orderBy('payment_description', $conds['order_type']);
            } elseif ($conds['order_by'] == 'status') {
                $query->orderBy('payment_status', $conds['order_type']);
            } elseif ($conds['order_by'] == 'in_app_purchase_prd_id') {
                $query->orderBy('iap_product_id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'type') {
                $query->orderBy('type', $conds['order_type']);
            } elseif ($conds['order_by'] == 'days') {
                // dd("here");
                // $query->orderBy('days', $conds['order_type']);
                // $query->orderByRaw('section::int",' desc');
                $query->orderBy('days', $conds['order_type']);
            } elseif ($conds['order_by'] == 'count') {

                $query->orderBy('count', $conds['order_type']);
            } elseif ($conds['order_by'] == 'price') {
                $query->orderBy('price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'currency_id') {
                $query->orderBy('currency_id', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'sale_price') {
                $query->orderBy('sale_price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'duration') {
                $query->orderBy('duration', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'discount_price') {
                $query->orderBy('discount_price', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } elseif ($conds['order_by'] == 'is_most_popular_plan') {
                $query->orderBy('is_most_popular_plan', $conds['order_type']);
                // $query->orderByRaw("CAST(price+1) ASC");
            } else {
                $query->orderBy('psx_payment_infos.' . $conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getPaymentInfos($relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $attribute = null, $serviceFrom = null)
    {

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $param = [$relations, $limit, $offset, $conds, $noPagination, $pagPerPage, $attribute, $serviceFrom, $sort];

        return PsCache::remember(
            [PaymentInfoCache::BASE],
            PaymentInfoCache::GET_ALL_EXPIRY,
            $param,
            function () use ($relations, $limit, $offset, $conds, $noPagination, $pagPerPage, $attribute, $serviceFrom, $sort) {

                $paymentInfos = PaymentInfo::select($this->paymentInfoTableName . '.*', $this->paymentAttributeTableName . '.' . $this->paymentAttributeAttributeValueCol . ' as payment_status')
                    ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                        if ($sort == 'title' || $sort == 'description') {
                            $q->leftjoin($this->coreKeyTableName, $this->paymentInfoTableName . '.' . $this->paymentInfoCoreKeysIdCol, '=', $this->coreKeyTableName . '.' . $this->coreKeyCoreKeysIdCol);
                            $q->select($this->coreKeyTableName . '.' . $this->coreKeyNameCol . ' as payment_title', $this->coreKeyTableName . '.' . $this->coreKeyDescriptionCol . ' as payment_description', $this->paymentInfoTableName . '.*');
                        }
                        if ($sort == 'in_app_purchase_prd_id') {
                            $q->leftjoin($this->coreKeyTableName, $this->paymentInfoTableName . '.' . $this->paymentInfoCoreKeysIdCol, '=', $this->coreKeyTableName . '.' . $this->coreKeyCoreKeysIdCol);
                            $q->select($this->coreKeyTableName . '.' . $this->coreKeyNameCol . ' as iap_product_id', $this->paymentInfoTableName . '.*');
                        }
                    })

                    ->when($relations, function ($query, $relations) {

                        // dd($relations);
                        // $query->with(['core_key','statusAttribute'=>function($query){
                        //     $query->orderBy('attribute_value','desc');
                        // }]);
                        $query->with($relations);
                    })
                    ->when($attribute, function ($query, $attribute) {
                        $sql = $this->getSqlForCustomField($attribute);
                        // dd($sql);
                        $query->selectRaw($sql);
                    })
                    ->leftJoin($this->paymentAttributeTableName, $this->paymentInfoTableName . '.' . $this->paymentInfoCoreKeysIdCol, '=', $this->paymentAttributeTableName . '.' . $this->paymentAttributeCoreKeysIdCol)
                    ->groupBy($this->paymentInfoTableName . '.' . $this->paymentInfoIdCol)
                    ->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when(empty($sort), function ($query) use ($serviceFrom) {
                        if ($serviceFrom == 'PromotionIAP') {
                            $query->orderBy('added_date', 'desc');
                        } else {
                            $query->orderBy('added_date', 'desc');
                        }
                    });

                // ->latest('psx_payment_infos.added_date');

                if ($pagPerPage) {
                    $paymentInfos = $paymentInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    $paymentInfos = $paymentInfos->get();
                }

                // dd($paymentInfos);
                return $paymentInfos;
            }
        );
    }

    public function getPaymentInfo($id = null, $relations = null, $core_keys_id = null, $conds = null)
    {
        $paymentInfo = PaymentInfo::when($id, function ($query, $id) {
            $query->where($this->paymentInfoIdCol, $id);
        })->when($core_keys_id, function ($query, $core_keys_id) {
            $query->where($this->paymentInfoCoreKeysIdCol, $core_keys_id);
        })
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();

        return $paymentInfo;
    }

    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showUserCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
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
                array_push($showUserCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . '@@name';
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

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering('ordering', $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            'arrForColumnProps' => $sortedColumnArr,
            'arrForColumnFilterProps' => $hideShowCoreAndCustomFieldArr,
            'showCoreField' => $showUserCols,
        ];

        return $dataArr;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiEnableCol, $this->enable)->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();

        return $hiddenShownForFields;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== 'Image' && $type !== 'MultiSelect' && $type !== 'Action') {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
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
