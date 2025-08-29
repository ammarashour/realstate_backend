<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\CoreKeyType;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Services\CoreKeyCounterService;
use Modules\Core\Transformers\Backend\Model\CoreKeyType\CoreKeyTypeWithKeyResource;

class CoreKeyTypeService extends PsService
{
    protected $coreKeyCounterService, $viewAnyAbility, $code, $hide, $show, $coreFieldFilterModuleNameCol, $coreFieldFilterIdCol, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreKeyTypeAddedDate;

    public function __construct(CoreKeyCounterService $coreKeyCounterService)
    {
        $this->coreKeyCounterService = $coreKeyCounterService;
        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->code = Constants::coreKeyType;
        $this->hide = Constants::hide;
        $this->show = Constants::show;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;
        $this->coreFieldFilterIdCol = CoreField::id;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreKeyTypeIdCol = CoreKeyType::id;
        $this->coreKeyTypeTableName = CoreKeyType::tableName;
        $this->coreKeyTypeCodeCol = CoreKeyType::code;
        $this->coreKeyTypeNameCol = CoreKeyType::name;
        $this->coreKeyTypeAddedDate = CoreKeyType::addedDate;
        $this->coreKeyTypeDescriptionCol = CoreKeyType::description;
        $this->coreKeyTypeAddedDateCol = CoreKeyType::addedDate;
        $this->coreKeyTypeAddedUserIdCol = CoreKeyType::addedUserId;
        $this->coreKeyTypeUpdatedDateCol = CoreKeyType::updatedDate;
        $this->coreKeyTypeUpdatedUserIdCol = CoreKeyType::updatedUserId;
    }

    public function index($request)
    {
        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, CoreKeyType::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $coreKeyTypes = CoreKeyTypeWithKeyResource::collection($this->getCoreKeyTypes(null, null, $conds, false, $row));

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
                "coreKeyTypes" => $coreKeyTypes,
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
                "coreKeyTypes" => $coreKeyTypes,
                'search' => $conds['searchterm'],
            ];
        }

        return $dataArr;
    }

    public function create()
    {

        // check permission start
        $checkPermission = $this->checkPermission($this->createAbility, CoreKeyType::class, "admin.index");
        // check permission end

        $code = $this->code;

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);

        $dataArr = [
            "checkPermission" => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

        return $dataArr;
    }

    public function store($request)
    {

        DB::beginTransaction();

        try {
            $coreKeyType = new CoreKeyType();
            $coreKeyType->name = $request->name;
            $coreKeyType->description = $request->description;
            $coreKeyType->code = $request->code;
            $coreKeyType->added_user_id = Auth::user()->id;
            $coreKeyType->save();

            // save code and counter to core_key_counter table
            $this->storeCoreKeyCounter($request->code);

            DB::commit();
            return $coreKeyType;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function storeCoreKeyCounter($code)
    {
        $coreKeyCounter = new \stdClass();
        $coreKeyCounter->code = $code;
        $coreKeyCounter->counter = 0;
        $this->coreKeyCounterService->store($coreKeyCounter);
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->coreKeyTypeTableName . '.' . $this->coreKeyTypeCodeCol, 'like', '%' . $search . '%');
                $query->orWhere($this->coreKeyTypeTableName . '.' . $this->coreKeyTypeNameCol, 'like', '%' . $search . '%');
                $query->orWhere($this->coreKeyTypeTableName . '.' . $this->coreKeyTypeDescriptionCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas($this->coreKeyTypeAddedDateCol, function ($q) use ($date_filter) {
                $q->where($this->coreKeyTypeAddedDateCol, $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->coreKeyTypeAddedUserIdCol, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy($this->coreKeyTypeIdCol, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getCoreKeyTypes($limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $coreKeyTypes = CoreKeyType::when($conds, function ($query, $conds) {
            $query = $this->searching($query, $conds);
        })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest();

        if ($pagPerPage) {
            $coreKeyTypes = $coreKeyTypes->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $coreKeyTypes = $coreKeyTypes->get();
        }

        return $coreKeyTypes;
    }

    public function getCoreKeyType($id, $conds = null)
    {
        $coreKeyType = CoreKeyType::where(CoreKeyType::id, $id)
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $coreKeyType;
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
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action");
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
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;
                $ordering = $showFields->coreField->ordering;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        // dd($showCoreAndCustomFieldArr);
        // $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $showCoreAndCustomFieldArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showUserCols,
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

    public function takingForColumnProps($label, $field, $type)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
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

    public function getCoreFieldFilteredLists($code)
    {
        return CoreField::where($this->coreFieldFilterModuleNameCol, $code)->get();
    }
}
