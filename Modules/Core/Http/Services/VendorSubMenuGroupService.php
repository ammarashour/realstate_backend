<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Transformers\Backend\Model\VendorSubMenuGroup\VendorSubMenuGroupWithKeyResource;

class VendorSubMenuGroupService extends PsService
{
    protected $iconService, $yes, $no, $moduleService, $successFlag, $publish, $unPublish, $dangerFlag, $menuGroupService, $subMenuGroupIdCol, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct(VendorMenuGroupService $menuGroupService, IconService $iconService, VendorModuleService $moduleService)
    {
        $this->menuGroupService = $menuGroupService;
        $this->moduleService =  $moduleService;
        $this->iconService = $iconService;
        $this->subMenuGroupIdCol = VendorSubMenuGroup::id;
        $this->dangerFlag = Constants::danger;
        $this->successFlag = Constants::success;
        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->yes = Constants::yes;
        $this->no = Constants::no;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::vendorSubMenuGroup;

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

        $this->subMenuTableName = VendorSubMenuGroup::tableName;
        $this->subMenuVendorMenuGroupIdCol = VendorSubMenuGroup::coreMenuGroupId;
        $this->subMenuIsShowOnMenuCol = VendorSubMenuGroup::isShowOnMenu;
        $this->subMenuSubMenuNameCol = VendorSubMenuGroup::subMenuName;


        $this->menuGroupIdCol = VendorMenuGroup::id;
        $this->menuGroupTableName = VendorMenuGroup::tableName;
        $this->menuGroupGroupNameCol = VendorMenuGroup::groupName;
    }

    public function store($request)
    {

        DB::beginTransaction();

        try {
            $subMenuGroup = new VendorSubMenuGroup();
            $subMenuGroup->sub_menu_name = $request->sub_menu_name;
            $subMenuGroup->sub_menu_desc = $request->sub_menu_desc;
            $subMenuGroup->is_dropdown = $request->is_dropdown;
            $subMenuGroup->module_id = $request->module_id;
            $subMenuGroup->icon_id = $request->icon_id;
            $subMenuGroup->sub_menu_lang_key = $request->sub_menu_lang_key;
            $subMenuGroup->ordering = $request->ordering;
            $subMenuGroup->is_show_on_menu = $request->is_show_on_menu;
            $subMenuGroup->core_menu_group_id = $request->core_menu_group_id;
            $subMenuGroup->added_user_id = Auth::user()->id;
            $subMenuGroup->save();

            $module = $this->moduleService->getModule($subMenuGroup->module_id);
            if (!empty($module)) {
                $module->sub_menu_id = $subMenuGroup->id;
                $module->update();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return ['error' => $e->getMessage()];
        }

        return $subMenuGroup;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $subMenuGroup = $this->getSubMenuGroup($id);
            $subMenuGroup->sub_menu_name = $request->sub_menu_name;
            $subMenuGroup->sub_menu_desc = $request->sub_menu_desc;
            $subMenuGroup->is_dropdown = $request->is_dropdown;
            $subMenuGroup->module_id = $request->module_id;
            $subMenuGroup->icon_id = $request->icon_id;
            $subMenuGroup->sub_menu_lang_key = $request->sub_menu_lang_key;
            $subMenuGroup->ordering = $request->ordering;
            $subMenuGroup->is_show_on_menu = $request->is_show_on_menu;
            $subMenuGroup->core_menu_group_id = $request->core_menu_group_id;
            $subMenuGroup->updated_user_id = Auth::user()->id;
            $subMenuGroup->update();

            $module = $this->moduleService->getModule($request->module_id);
            if (!empty($module)) {
                $module->sub_menu_id = $subMenuGroup->id;
                $module->update();
            } else {
                $moduleBySubMenuId = $this->moduleService->getModule(null, $subMenuGroup->id);
                $moduleBySubMenuId->sub_menu_id = 0;
                $moduleBySubMenuId->update();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $subMenuGroup;
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

    public function getSubMenuGroups($relation = null, $pagPerPage = null, $conds = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        // dd($sort);
        $subMenuGroups = VendorSubMenuGroup::select('psx_vendor_sub_menus.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                    $q->leftJoin($this->userTableName, $this->userTableName . '.' . $this->userIdCol, '=', $this->langTableName . '.' . $conds['order_by']);
                    $q->select($this->userTableName . '.' . $this->userNameCol . ' as owner', $this->langTableName . '.*');
                }
                if ($conds['order_by'] == 'core_menu_group_id@@group_name') {
                    $q->join($this->menuGroupTableName, $this->menuGroupTableName . '.' . $this->menuGroupIdCol, '=', $this->subMenuTableName . '.' . $this->subMenuVendorMenuGroupIdCol);
                    $q->select($this->menuGroupTableName . '.' . $this->menuGroupGroupNameCol . ' as menu_group_name', $this->subMenuTableName . '.*');
                }
            })->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->subMenuIsShowOnMenuCol, 'desc')->orderBy($this->subMenuSubMenuNameCol, 'asc');
            });
        if ($pagPerPage) {
            $subMenuGroups = $subMenuGroups->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $subMenuGroups = $subMenuGroups->get();
        }
        return $subMenuGroups;
    }
    public function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where('sub_menu_name', 'like', '%' . $search . '%');
            });
        }
        if (isset($conds['menu_id'])  && $conds['menu_id'] != "all") {
            $query->where($this->subMenuVendorMenuGroupIdCol, $conds['menu_id']);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id') {
                $query->orderBy('owner', $conds['order_type']);
            } elseif ($conds['order_by'] == 'core_menu_group_id@@group_name') {
                $query->orderBy('menu_group_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }
        return $query;
    }

    public function getSubMenuGroup($id = null)
    {
        $subMenuGroup = VendorSubMenuGroup::when($id, function ($q, $id) {
            $q->where($this->subMenuGroupIdCol, $id);
        })->first();
        return $subMenuGroup;
    }

    public function index($request)
    {

        $checkPermission = $this->checkPermission($this->viewAnyAbility, VendorSubMenuGroup::class, "admin.index");
        // check permission end
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['menu_id'] = $request->input('menu_filter') == 'all' ? null  : $request->menu_filter;


        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $menu_groups = $this->menuGroupService->getMenuGroups();
        $relations = ['core_menu_group', 'owner', 'editor'];
        $sub_menu_groups = VendorSubMenuGroupWithKeyResource::collection($this->getSubMenuGroups($relations, $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showSubMenuGroupCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];
        if ($conds['order_by']) {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'sub_menu_groups' => $sub_menu_groups,
                'menu_groups' => $menu_groups,
                'showSubMenuGroupCols' => $showSubMenuGroupCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedMenu' => $conds['menu_id'],
            ];
        } else {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'sub_menu_groups' => $sub_menu_groups,
                'menu_groups' => $menu_groups,
                'showSubMenuGroupCols' => $showSubMenuGroupCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedMenu' => $conds['menu_id'],
            ];
        }

        return $dataArr;
    }

    public function create()
    {
        $menuGroups = $this->menuGroupService->getMenuGroups();
        $code = $this->code;
        $icons = $this->iconService->getIcons();
        $modules = $this->moduleService->getModules(null, null, null, $this->publish, $this->yes);
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);

        $checkPermission = $this->checkPermission($this->createAbility, VendorSubMenuGroup::class, "admin.index");
        // check permission end
        $dataArr = [
            'menu_groups' => $menuGroups,
            'modules' => $modules,
            'icons' => $icons,
            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
        return $dataArr;
    }

    public function edit($id)
    {
        $menuGroups = $this->menuGroupService->getMenuGroups();
        $subMenuGroup = $this->getSubMenuGroup($id);
        $code = $this->code;
        $icons = $this->iconService->getIcons();
        $modules = $this->moduleService->getModules(null, null, null, $this->publish, $this->yes);
        $forSelectedModules = $this->moduleService->getModules();
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        // check permission start
        $checkPermission = $this->checkPermission($this->editAbility, $subMenuGroup, "admin.index");
        // check permission end
        $dataArr = [
            'modules' => $modules,
            'forSelectedModules' => $forSelectedModules,
            'icons' => $icons,
            'sub_menu_group' => $subMenuGroup,
            'menu_groups' => $menuGroups,
            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
        return $dataArr;
    }

    public function destroy($id)
    {
        $subMenuGroup = $this->getSubMenuGroup($id);
        $module = $this->moduleService->getModule($subMenuGroup->module_id);

        $checkPermission = $this->checkPermission($this->deleteAbility, $subMenuGroup, "admin.index");
        if (!empty($checkPermission)) {
            $dataArr = [
                "checkPermission" => $checkPermission,
            ];
            return $dataArr;
        }
        $name = $subMenuGroup->group_name;

        DB::beginTransaction();

        try {
            $subMenuGroup->delete();

            $module->sub_menu_id = 0;
            $module->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $status = [
                'msg' => $e->getMessage(),
                'flag' => $this->dangerFlag,
            ];

            return $status;
        }


        $status = [
            'flag' => $this->dangerFlag,
            // 'msg' => 'The '.$name.' row has been deleted successfully.',
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            "checkPermission" => $checkPermission,
        ];

        return $status;
    }

    public function statusChange($id)
    {
        $subMenuGroup = $this->getSubMenuGroup($id);

        if ($subMenuGroup->is_show_on_menu == $this->publish) {
            $subMenuGroup->is_show_on_menu = $this->unPublish;
        } else {
            $subMenuGroup->is_show_on_menu = $this->publish;
        }
        $subMenuGroup->updated_user_id = Auth::user()->id;
        $subMenuGroup->update();

        $status = [
            // 'msg' => 'The status of '.$subMenuGroup->sub_menu_desc.' row has been updated successfully.',
            'msg' =>  __('core__be_status_attribute_updated', ['attribute' => $subMenuGroup->sub_menu_desc]),
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
