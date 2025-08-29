<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\config\ps_constant;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Http\Services\IconService;
use App\Http\Contracts\Menu\ModuleServiceInterface;
use App\Http\Contracts\Menu\MenuGroupServiceInterface;
use Modules\Core\Http\Requests\Menu\StoreSubMenuGroupRequest;
use App\Http\Contracts\Menu\SubMenuGroupServiceInterface;
use Modules\Core\Http\Requests\Menu\UpdateSubMenuGroupRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Transformers\Backend\Model\Menu\SubMenuGroupWithKeyResource;

class SubMenuGroupController extends PsController
{
    private const parentPath = 'sub_menu_group';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(protected SubMenuGroupServiceInterface $subMenuGroupService,
        protected MenuGroupServiceInterface $menuGroupService,
        protected ModuleServiceInterface $moduleService,
        protected IconService $iconService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(CoreSubMenuGroup::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(CoreSubMenuGroup::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreSubMenuGroupRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Menu Group
            $this->subMenuGroupService->save(subMenuGroupData : $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $subMenuGroup = $this->subMenuGroupService->get($id);

        $this->handlePermissionWithModel($subMenuGroup, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateSubMenuGroupRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->subMenuGroupService->update(
                id : $id,
                subMenuGroupData : $validatedData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $subMenuGroup = $this->subMenuGroupService->get($id);

            $this->handlePermissionWithModel($subMenuGroup, Constants::deleteAbility);

            $dataArr = $this->subMenuGroupService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $subMenuGroup = $this->subMenuGroupService->get($id);

            $this->handlePermissionWithModel($subMenuGroup, Constants::editAbility);

            $status = $this->prepareStatusData($subMenuGroup);

            $this->subMenuGroupService->setStatus($id, $status);

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

        $menuGroups = $this->menuGroupService->getAll();
        // manipulate menu-group data
        $relations = ['core_menu_group', 'owner', 'editor'];

        $subMenuGroups = SubMenuGroupWithKeyResource::collection($this->subMenuGroupService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds,
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::coreSubMenuGroup, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createCoreSubMenu' => 'create-coreSubMenuGroup',
        ];

        // dd($columnAndColumnFilter[ps_constant::handlingColumn]);
        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sub_menu_groups' => $subMenuGroups,
            'menu_groups' => $menuGroups,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'selectedMenu' => $conds['menu_id'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $menuGroups = $this->menuGroupService->getAll();
        $modules = $this->moduleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $icons = $this->iconService->getIcons();
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::coreSubMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'menu_groups' => $menuGroups,
            'modules' => $modules,
            'icons' => $icons,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {

        $menuGroups = $this->menuGroupService->getAll();
        $modules = $this->moduleService->getAll(null, null, null, Constants::publish, Constants::yes);
        $forSelectedModules = $this->moduleService->getAll();
        $icons = $this->iconService->getIcons();
        $subMenuGroup = $this->subMenuGroupService->get($id);
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::coreSubMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'menu_groups' => $menuGroups,
            'modules' => $modules,
            'forSelectedModules' => $forSelectedModules,
            'icons' => $icons,
            'sub_menu_group' => $subMenuGroup,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareStatusData($subMenuGroup)
    {
        return $subMenuGroup->is_show_on_menu == Constants::publish
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
