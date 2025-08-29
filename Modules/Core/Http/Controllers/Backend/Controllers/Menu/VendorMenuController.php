<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\VendorMenuServiceInterface;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;
use App\Http\Contracts\Menu\VendorSubMenuGroupServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorMenu;
use Modules\Core\Http\Requests\Menu\StoreVendorMenuRequest;
use Modules\Core\Http\Requests\Menu\UpdateVendorMenuRequest;
use Modules\Core\Http\Services\IconService;
use Modules\Core\Transformers\Backend\Model\Menu\VendorMenuWithKeyResource;

class VendorMenuController extends PsController
{
    private const parentPath = 'vendor_menu';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(
        protected VendorMenuServiceInterface $vendorMenuService,
        protected VendorSubMenuGroupServiceInterface $vendorSubMenuGroupService,
        protected VendorModuleServiceInterface $vendorModuleService,
        protected IconService $iconService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorMenu::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(VendorMenu::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreVendorMenuRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Vendor Menu
            $this->vendorMenuService->save(vendorMenuData : $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $vendorMenu = $this->vendorMenuService->get($id);

        $this->handlePermissionWithModel($vendorMenu, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorMenuRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->vendorMenuService->update(
                id : $id,
                vendorMenuData : $validatedData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $vendorMenu = $this->vendorMenuService->get($id);

            $this->handlePermissionWithModel($vendorMenu, Constants::deleteAbility);

            $dataArr = $this->vendorMenuService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $vendorMenu = $this->vendorMenuService->get($id);

            $this->handlePermissionWithModel($vendorMenu, Constants::editAbility);

            $status = $this->prepareStatusData($vendorMenu);

            $this->vendorMenuService->setStatus($id, $status);

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
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'sub_menu_id' => $request->input('sub_menu_filter') == 'all' ? null : $request->input('sub_menu_filter'),
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $sub_menu_groups = $this->vendorSubMenuGroupService->getAll();

        // manipulate menu data
        $relations = ['core_sub_menu_group', 'owner', 'editor'];
        $vendorMenus = VendorMenuWithKeyResource::collection($this->vendorMenuService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds,
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorMenu, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorMenu' => 'create-vendorMenu',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'modules' => $vendorMenus,
            'sub_menu_groups' => $sub_menu_groups,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'selectedSubMenu' => $conds['sub_menu_id'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $subMenuGroups = $this->vendorSubMenuGroupService->getAll();
        $icons = $this->iconService->getIcons();
        $modules = $this->vendorModuleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorMenu,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'sub_menu_groups' => $subMenuGroups,
            'modules' => $modules,
            'icons' => $icons,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $vendorMenu = $this->vendorMenuService->get($id);
        $vendorSubMenuGroups = $this->vendorSubMenuGroupService->getAll();
        $icons = $this->iconService->getIcons();
        $vendorModules = $this->vendorModuleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $forSelectedModules = $this->vendorModuleService->getAll();
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorMenu,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'menu' => $vendorMenu,
            'modules' => $vendorModules,
            'forSelectedModules' => $forSelectedModules,
            'icons' => $icons,
            'sub_menu_groups' => $vendorSubMenuGroups,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareStatusData($menuGroup)
    {
        return $menuGroup->is_show_on_menu == Constants::publish
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
}
