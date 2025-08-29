<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use App\Http\Contracts\Menu\MenuGroupServiceInterface;
use Modules\Core\Http\Requests\Menu\StoreMenuGroupRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Http\Requests\Menu\UpdateMenuGroupRequest;
use Modules\Core\Transformers\Backend\Model\Menu\MenuGroupWithKeyResource;

class MenuGroupController extends PsController
{
    private const parentPath = 'menu_group';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(protected MenuGroupServiceInterface $menuGroupService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(CoreMenuGroup::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(CoreMenuGroup::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreMenuGroupRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Menu Group
            $this->menuGroupService->save(menuGroupData : $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $menuGroup = $this->menuGroupService->get($id);

        $this->handlePermissionWithModel($menuGroup, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateMenuGroupRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->menuGroupService->update(
                id : $id,
                menuGroupData : $validatedData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $menuGroup = $this->menuGroupService->get($id);

            $this->handlePermissionWithModel($menuGroup, Constants::deleteAbility);

            $dataArr = $this->menuGroupService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $menuGroup = $this->menuGroupService->get($id);

            $this->handlePermissionWithModel($menuGroup, Constants::editAbility);

            $status = $this->prepareStatusData($menuGroup);

            $this->menuGroupService->setStatus($id, $status);

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
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate menu-group data
        $relations = ['owner', 'editor'];

        $menuGroups = MenuGroupWithKeyResource::collection($this->menuGroupService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::coreMenuGroup, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createCoreMenu' => 'create-coreMenuGroup',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'menu_groups' => $menuGroups,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::coreMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::coreMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        $menuGroup = $this->menuGroupService->get($id);

        return [
            'menu_group' => $menuGroup,
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
