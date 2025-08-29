<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\VendorMenuGroupServiceInterface;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;
use App\Http\Contracts\Menu\VendorSubMenuGroupServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorSubMenuGroup;
use Modules\Core\Http\Requests\Menu\StoreVendorSubMenuGroupRequest;
use Modules\Core\Http\Requests\Menu\UpdateVendorSubMenuGroupRequest;
use Modules\Core\Http\Services\IconService;
use Modules\Core\Transformers\Backend\Model\VendorSubMenuGroup\VendorSubMenuGroupWithKeyResource;

class VendorSubMenuGroupController extends PsController
{
    private const parentPath = 'vendor_sub_menu_group';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(
        protected VendorSubMenuGroupServiceInterface $vendorSubMenuGroupService,
        protected VendorMenuGroupServiceInterface $vendorMenuGroupService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected VendorModuleServiceInterface $vendorModuleService,
        protected IconService $iconService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorSubMenuGroup::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(VendorSubMenuGroup::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreVendorSubMenuGroupRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save vendor sub menu group
            $this->vendorSubMenuGroupService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }

    }

    public function edit($id)
    {
        // check permission start
        $vendorSubMenuGroup = $this->vendorSubMenuGroupService->get($id);

        $this->handlePermissionWithModel($vendorSubMenuGroup, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorSubMenuGroupRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->vendorSubMenuGroupService->update($id, $validatedData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $vendorSubMenuGroup = $this->vendorSubMenuGroupService->get($id);

            $this->handlePermissionWithModel($vendorSubMenuGroup, Constants::deleteAbility);

            $dataArr = $this->vendorSubMenuGroupService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }

    public function statusChange($id)
    {
        try {

            $vendorSubMenuGroup = $this->vendorSubMenuGroupService->get($id);

            $this->handlePermissionWithModel($vendorSubMenuGroup, Constants::editAbility);

            $status = $this->prepareStatusData($vendorSubMenuGroup);

            $this->vendorSubMenuGroupService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function screenDisplayUiStore(Request $request)
    {
        makeColumnHideShown($request);

        return redirect()->back();
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
            'menu_id' => $request->input('menu_filter') == 'all' ? null : $request->input('menu_filter'),
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $vendorMenuGroups = $this->vendorMenuGroupService->getAll(relation: null, pagPerPage: null, conds: null);
        // manipulate vendor sub menu group data
        $relations = ['core_menu_group', 'owner', 'editor'];
        $vendorSubMenuGroups = VendorSubMenuGroupWithKeyResource::collection($this->vendorSubMenuGroupService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorSubMenuGroup, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorSubMenuGroup' => 'create-vendorSubMenuGroup',
        ];

        return [
            'menu_groups' => $vendorMenuGroups,
            'sub_menu_groups' => $vendorSubMenuGroups,
            'selectedMenu' => $conds['menu_id'],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $vendorMenuGroups = $this->vendorMenuGroupService->getAll(null, null, null);
        $modules = $this->vendorModuleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $icons = $this->iconService->getIcons();
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorSubMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'menu_groups' => $vendorMenuGroups,
            'modules' => $modules,
            'icons' => $icons,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $vendorSubMenuGroup = $this->vendorSubMenuGroupService->get($id);
        $vendorMenuGroups = $this->vendorMenuGroupService->getAll(null, null, null);
        $modules = $this->vendorModuleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $forSelectedModules = $this->vendorModuleService->getAll();
        $icons = $this->iconService->getIcons();
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorSubMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'sub_menu_group' => $vendorSubMenuGroup,
            'menu_groups' => $vendorMenuGroups,
            'forSelectedModules' => $forSelectedModules,
            'modules' => $modules,
            'icons' => $icons,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareStatusData($vendorSubMenuGroup)
    {
        return $vendorSubMenuGroup->is_show_on_menu == Constants::publish
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

    // public function store(Request $request)
    // {
    //     // validation start
    //     $errors = validateForCustomField($this->code,$request->sub_menu_group_relation);

    //     $coreFieldsIds = [];
    //     $errors = [];

    //     $cond['module_name'] = $this->code;
    //     $cond['mandatory'] = 1;
    //     $cond['is_core_field'] = 1;

    //     $coreFields = $this->coreFieldService->getCoreFieldsWithConds($cond);

    //     foreach ($coreFields as $key => $value){
    //         if (str_contains($value->field_name,"@@")) {
    //             $originFieldName = strstr($value->field_name,"@@",true);
    //         } else {
    //             $originFieldName = $value->field_name;
    //         }
    //         array_push($coreFieldsIds,$originFieldName);

    //     }

    //     $validationArr = [];

    //     if(in_array('sub_menu_name',$coreFieldsIds)){
    //         $validationArr['sub_menu_name'] = 'required|min:3|unique:psx_vendor_sub_menus,sub_menu_name,';
    //     }

    //     if(in_array('core_menu_group_id',$coreFieldsIds)){
    //         $validationArr['core_menu_group_id'] = 'required';
    //     }

    //     if(in_array('sub_menu_icon',$coreFieldsIds)){
    //         $validationArr['icon_id'] = 'required';
    //     }

    //     if(in_array('ordering',$coreFieldsIds)){
    //         $validationArr['ordering'] = 'integer';
    //     }

    //     // change custom attribute if required start
    //     $attributes['sub_menu_name'] = "Sub Menu Name";
    //     $attributes['core_menu_group_id'] = "Menu Group Id";
    //     $attributes['icon_id'] = "Icon";
    //     // change custom attribute if required end

    //     $validator = Validator::make($request->all(),$validationArr,[], $attributes);

    //     if ($validator->fails()) {
    //         return redirect()->route(self::createRoute)->with('sub_menu_group_relation_errors',$errors)
    //             ->withErrors($validator)
    //             ->withInput();
    //     } else {

    //         if (collect($errors)->isNotEmpty()){
    //             return redirect()->route(self::createRoute)->with('sub_menu_group_relation_errors',$errors);
    //         }
    //     }

    //     // validation end

    //     $subMenuGroup = $this->subMenuGroupService->store($request);

    //     // if have error
    //     if (isset($subMenuGroup['error'])){
    //         $msg = $subMenuGroup['error'];

    //         return redirectView(self::indexRoute, $msg, $this->dangerFlag);
    //     }

    //     return redirectView(self::indexRoute, $subMenuGroup);
    // }

    //    public function show(CoreSubMenuGroup $sub_menu_group)
    //    {
    //        return redirect()->route('sub_menu_group.edit', $sub_menu_group);
    //    }

    // public function update(Request $request, $id)
    // {
    //     // validation start
    //     $errors = validateForCustomField($this->code,$request->sub_menu_group_relation);

    //     $coreFieldsIds = [];
    //     $errors = [];

    //     $cond['module_name'] = $this->code;
    //     $cond['mandatory'] = 1;
    //     $cond['is_core_field'] = 1;

    //     $coreFields = $this->coreFieldService->getCoreFieldsWithConds($cond);

    //     foreach ($coreFields as $key => $value){
    //         if (str_contains($value->field_name,"@@")) {
    //             $originFieldName = strstr($value->field_name,"@@",true);
    //         } else {
    //             $originFieldName = $value->field_name;
    //         }
    //         array_push($coreFieldsIds,$originFieldName);

    //     }

    //     $validationArr = [];

    //     if(in_array('sub_menu_name',$coreFieldsIds)){
    //         $validationArr['sub_menu_name'] = 'required|min:3|unique:psx_vendor_sub_menus,sub_menu_name,'.$id;
    //     }

    //     if(in_array('core_menu_group_id',$coreFieldsIds)){
    //         $validationArr['core_menu_group_id'] = 'required';
    //     }

    //     if(in_array('sub_menu_icon',$coreFieldsIds)){
    //         $validationArr['icon_id'] = 'required';
    //     }

    //     if(in_array('ordering',$coreFieldsIds)){
    //         $validationArr['ordering'] = 'integer';
    //     }

    //     // change custom attribute if required start
    //     $attributes['sub_menu_name'] = "Sub Menu Name";
    //     $attributes['core_menu_group_id'] = "Menu Group Id";
    //     $attributes['icon_id'] = "Icon";
    //     // change custom attribute if required end

    //     $validator = Validator::make($request->all(),$validationArr,[], $attributes);

    //     if ($validator->fails()) {
    //         return redirect()->route(self::editRoute, $id)->with('sub_menu_group_relation_errors',$errors)
    //             ->withErrors($validator)
    //             ->withInput();
    //     } else {

    //         if (collect($errors)->isNotEmpty()){
    //             return redirect()->route(self::editRoute, $id)->with('sub_menu_group_relation_errors',$errors);
    //         }
    //     }

    //     // validation end

    //     $currency = $this->subMenuGroupService->update($id, $request);

    //     // if have error
    //     if ($currency){
    //         $msg = $currency;
    //         return redirectView(self::indexRoute, $msg, $this->dangerFlag);
    //     }

    //     return redirectView(self::indexRoute, $currency);
    // }

}
