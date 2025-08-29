<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Menu\VendorMenu;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Entities\Menu\VendorModule;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Transformers\Backend\Model\VendorModule\VendorModuleWithKeyResource;
use Modules\Core\Entities\Configuration\MobileSetting;
use Carbon\Carbon;

class VendorModuleService extends PsService
{
    protected $warningFlag, $unPublish, $publish, $successFlag, $dangerFlag, $coreModuleIdCol, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct()
    {
        $this->coreModuleIdCol = VendorMenu::id;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->successFlag = Constants::success;
        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::vendorModuleKey;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;

        $this->userTableName = User::tableName;
        $this->userIdCol = User::id;
        $this->userNameCol = User::name;

        $this->langTableName = Language::tableName;
        $this->langIdCol = Language::id;

        $this->moduleIdCol = VendorModule::id;
        $this->moduleMenuIdCol = VendorModule::menuId;
        $this->moduleSubMenuIdCol = VendorModule::subMenuId;
        $this->moduleStatusCol = VendorModule::status;
        $this->moduleIsNotFromSideBarCol = VendorModule::isNotFromSidebar;
        $this->moduleTitleCol = VendorModule::title;
        $this->moduleRouteNameCol = VendorModule::routeName;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $timestamp = Carbon::now()->getTimestamp();
            $module = new VendorModule();
            $module->id = 'ui-' . $timestamp;
            $module->title = $request->title;
            $module->route_name = $request->route_name;
            $module->lang_key = $request->lang_key;
            $module->status = $request->status;
            $module->is_not_from_sidebar = $request->is_not_from_sidebar;
            $module->added_user_id = Auth::user()->id;
            $module->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $module;
    }

    public function update($moduleId, $request)
    {
        DB::beginTransaction();

        try {
            $module = $this->getModule($moduleId);
            $module->title = $request->title;
            $module->lang_key = $request->lang_key;
            $module->route_name = $request->route_name;
            $module->is_not_from_sidebar = $request->is_not_from_sidebar;
            $module->status = $request->status;
            $module->added_user_id = Auth::user()->id;
            $module->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $module;
    }

    public function getModules($relation = null, $pagPerPage = null, $conds = null, $status = null, $isNotUsedModules = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $modules = VendorModule::when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
            if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                $q->leftJoin($this->userTableName, $this->userTableName . '.' . $this->userIdCol, '=', $this->langTableName . '.' . $conds['order_by']);
                $q->select($this->userTableName . '.' . $this->userNameCol . ' as owner', $this->langTableName . '.*');
            }
        })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->when($status, function ($q, $status) {
                $q->where($this->moduleStatusCol, 1);
            })->when($isNotUsedModules, function ($q, $isNotUsedModules) {
                $q->where(function ($q) {
                    $q->where($this->moduleMenuIdCol, "=", 0)->orWhere($this->moduleMenuIdCol, "=", null);
                })->where(function ($q) {
                    $q->where($this->moduleSubMenuIdCol, "=", 0)->orWhere($this->moduleSubMenuIdCol, "=", null);
                })->where(function ($q) {
                    $q->where($this->moduleIsNotFromSideBarCol, "=", 0)->orWhere($this->moduleIsNotFromSideBarCol, "=", null);
                });
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->moduleStatusCol, 'desc')->orderBy($this->moduleTitleCol, 'asc');
            });
        if ($pagPerPage) {
            $modules = $modules->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $modules = $modules->get();
        }

        return $modules;
    }
    public function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->moduleTitleCol, 'like', '%' . $search . '%')
                    ->orWhere($this->moduleRouteNameCol, 'like', '%' . $search . '%');
            });
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } else {

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }
        return $query;
    }

    public function getModule($id = null, $subMenuId = null, $isNotUsedModules = null)
    {
        $module = VendorModule::when($id, function ($q, $id) {
            $q->where($this->coreModuleIdCol, $id);
        })
            ->when($subMenuId, function ($q, $subMenuId) {
                $q->where($this->moduleSubMenuIdCol, $subMenuId);
            })
            ->when($isNotUsedModules, function ($q, $isNotUsedModules) {
                $q->where(function ($q) {
                    $q->where($this->moduleMenuIdCol, "=", 0)->orWhere($this->moduleMenuIdCol, "=", null);
                })->where(function ($q) {
                    $q->where($this->moduleSubMenuIdCol, "=", 0)->orWhere($this->moduleSubMenuIdCol, "=", null);
                });
            })
            ->first();
        if ($id == null && $subMenuId == null) {
            return null;
        }
        return $module;
    }

    public function index($request)
    {
        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, VendorModule::class, "admin.index");
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $relations = ['owner', 'editor'];
        $modules = VendorModuleWithKeyResource::collection($this->getModules($relations, $row, $conds));
        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showModuleCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];
        if ($conds['order_by']) {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'modules' => $modules,
                'showModuleCols' => $showModuleCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'modules' => $modules,
                'showModuleCols' => $showModuleCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'search' => $conds['searchterm'],
            ];
        }
        return $dataArr;
    }

    public function create()
    {
        $code = $this->code;
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);

        $checkPermission = $this->checkPermission($this->createAbility, VendorModule::class, "admin.index");
        // check permission end
        $dataArr = [
            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
        return $dataArr;
    }

    public function edit($moduleId)
    {
        $module = $this->getModule($moduleId);
        $code = $this->code;
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        // check permission start
        $checkPermission = $this->checkPermission($this->editAbility, $module, "admin.index");
        $dataArr = [
            'module' => $module,
            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
        return $dataArr;
    }

    public function destroy($moduleId)
    {
        $module = $this->getModule($moduleId, null, 1);

        if (empty($module)) {
            $status = [
                "checkPermission" => '',
                'msg' => __('core__be_module_not_delete'),
                'flag' => $this->warningFlag,
            ];
            return $status;
        }

        $checkPermission = $this->checkPermission($this->deleteAbility, $module, "admin.index");
        if (!empty($checkPermission)) {
            $dataArr = [
                "checkPermission" => $checkPermission,
            ];
            return $dataArr;
        }

        if (!empty($module)) {
            $name = $module->title;
            $module->delete();
        }

        $status = [
            // 'msg' => 'The '.$name.' row has been deleted successfully.',
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            'flag' => $this->dangerFlag,
            "checkPermission" => $checkPermission,
        ];

        return $status;
    }
    public function getCoreFieldFilteredLists($code)
    {
        return CoreField::where($this->coreFieldFilterModuleNameCol, $code)->get();
    }

    public function statusChange($moduleId)
    {
        $module = $this->getModule($moduleId);
        if ($module->status == $this->publish) {
            $module->status = $this->unPublish;
        } else {
            $module->status = $this->publish;
        }
        $module->updated_user_id = Auth::user()->id;
        $module->update();

        $status = [
            // 'msg' => 'The status of '.$module->title.' row has been updated successfully.',
            'msg' =>  __('core__be_status_attribute_updated', ['attribute' => $module->title]),
            'flag' => $this->successFlag,
        ];

        return $status;
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
