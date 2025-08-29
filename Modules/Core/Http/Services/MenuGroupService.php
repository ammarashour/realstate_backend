<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Project;
use Modules\Core\Transformers\Backend\Model\MenuGroup\MenuGroupWithKeyResource;

class MenuGroupService extends PsService
{
    protected $dangerFlag, $successFlag, $publish, $unPublish, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct()
    {
        $this->dangerFlag = Constants::danger;
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
        $this->code = Constants::coreMenuGroup;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;

        $this->userTableName = User::tableName;
        $this->userIdCol = User::id;
        $this->userNameCol = User::name;

        $this->languageTableName = Language::tableName;

        $this->menuGroupIsShowOnMenuCol = CoreMenuGroup::isShowOnMenu;
        $this->menuGroupGroupNameCol = CoreMenuGroup::groupName;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $menuGroup = new CoreMenuGroup();
            $menuGroup->group_name = $request->group_name;
            // $menuGroup->group_icon = $request->group_icon;
            $menuGroup->group_lang_key = $request->group_lang_key;
            $menuGroup->is_show_on_menu = $request->is_show_on_menu;
            // $menuGroup->ordering = $request->ordering;
            $menuGroup->is_invisible_group_name = $request->is_invisible_group_name;
            $menuGroup->added_user_id = Auth::user()->id;
            $menuGroup->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $menuGroup;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $menuGroup = $this->getMenuGroup($id);
            $menuGroup->group_name = $request->group_name;
            $menuGroup->group_icon = $request->group_icon;
            $menuGroup->group_lang_key = $request->group_lang_key;
            $menuGroup->is_show_on_menu = $request->is_show_on_menu;
            $menuGroup->ordering = $request->ordering;
            $menuGroup->is_invisible_group_name = $request->is_invisible_group_name;
            $menuGroup->updated_user_id = Auth::user()->id;
            $menuGroup->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $menuGroup;
    }

    public function getMenuGroups($relation = null, $pagPerPage = null, $conds = null)
    {
        $sort = '';
        $project = Project::select("base_project_id")->first();
        $baseProjectIdsToHideVendor = ps_constant::baseProjectIdsToHideVendor;
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $menu_groups = CoreMenuGroup::where(function ($q) use ($project, $baseProjectIdsToHideVendor) {
            if (in_array($project->base_project_id, $baseProjectIdsToHideVendor)) {
                $q->whereNotIn(CoreMenuGroup::id, ps_constant::vendorMenuGroupIdsInAdminPanel);
            }
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'added_user_id' || $conds['order_by'] == 'updated_user_id') {
                    $q->leftJoin($this->userTableName, $this->userTableName . '.' . $this->userIdCol, '=', $this->languageTableName . '.' . $conds['order_by']);
                    $q->select($this->userTableName . '.' . $this->userNameCol . ' as owner', $this->languageTableName . '.*');
                }
            })->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->menuGroupIsShowOnMenuCol, 'desc')->orderBy($this->menuGroupGroupNameCol, 'asc');
            });
        if ($pagPerPage) {
            $menu_groups = $menu_groups->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $menu_groups = $menu_groups->get();
        }


        return $menu_groups;
    }
    public function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->menuGroupGroupNameCol, 'like', '%' . $search . '%');
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

    public function getMenuGroup($id = null, $relation = null, $conds = null)
    {
        $menu_group = CoreMenuGroup::when($id, function ($q, $id) {
            $q->where('id', $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->first();
        return $menu_group;
    }

    // --------------------
    public function index($request)
    {
        // check permission start
        $checkPermission = $this->checkPermission($this->viewAnyAbility, CoreMenuGroup::class, "admin.index");
        // check permission end

        // search filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }
        $relations = ['owner', 'editor'];
        $menu_groups = MenuGroupWithKeyResource::collection($this->getMenuGroups($relations, $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showMenuGroupCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];
        if ($conds['order_by']) {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'menu_groups' => $menu_groups,
                'showMenuGroupCols' => $showMenuGroupCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'menu_groups' => $menu_groups,
                'showMenuGroupCols' => $showMenuGroupCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'search' => $conds['searchterm'],
            ];
        }
        return $dataArr;
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

    public function edit($id)
    {
        $menuGroup = $this->getMenuGroup($id);
        $code = $this->code;
        // check permission start
        $checkPermission = $this->checkPermission($this->editAbility, $menuGroup, "admin.index");
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        // check permission end
        $dataArr = [
            'menu_group' => $menuGroup,
            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
        return $dataArr;
    }

    public function destroy($id)
    {
        $menuGroup = $this->getMenuGroup($id);
        $checkPermission = $this->checkPermission($this->deleteAbility, $menuGroup, "admin.index");
        if (!empty($checkPermission)) {
            $dataArr = [
                "checkPermission" => $checkPermission,
            ];
            return $dataArr;
        }
        $name = $menuGroup->group_name;
        $menuGroup->delete();
        $status = [
            // 'msg' => 'The '.$name.' row has been deleted successfully.',
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            'flag' => $this->dangerFlag,
            "checkPermission" => $checkPermission,
        ];

        return $status;
    }

    public function statusChange($id)
    {
        $menu_group = $this->getMenuGroup($id);

        if ($menu_group->is_show_on_menu == $this->publish) {
            $menu_group->is_show_on_menu = $this->unPublish;
        } else {
            $menu_group->is_show_on_menu = $this->publish;
        }
        $menu_group->updated_user_id = Auth::user()->id;
        $menu_group->update();

        $status = [
            // 'msg' => 'The status of '.$menu_group->group_name.' row has been updated successfully.',
            'msg' => $msg = __('core__be_status_attribute_updated', ['attribute' => $menu_group->group_name]),
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

    public function create()
    {

        $code = $this->code;
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        // check permission start
        $checkPermission = $this->checkPermission($this->createAbility, CoreMenuGroup::class, "admin.index");
        // check permission end
        $dataArr = [

            'checkPermission' => $checkPermission,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,

        ];
        return $dataArr;
    }
}
