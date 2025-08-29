<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\VendorMenuGroupServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorMenuGroup;
use Modules\Core\Http\Requests\StoreVendorMenuGroupRequest;
use Modules\Core\Http\Requests\UpdateVendorMenuGroupRequest;
use Modules\Core\Transformers\Backend\Model\VendorMenuGroup\VendorMenuGroupWithKeyResource;

class VendorMenuGroupController extends PsController
{
    private const parentPath = 'vendor_menu_group';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(
        protected VendorMenuGroupServiceInterface $vendorMenuGroupService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorMenuGroup::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(VendorMenuGroup::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreVendorMenuGroupRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save vendor menu group
            $this->vendorMenuGroupService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }

    }

    public function edit($id)
    {
        // check permission start
        $vendorMenuGroup = $this->vendorMenuGroupService->get($id);

        $this->handlePermissionWithModel($vendorMenuGroup, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorMenuGroupRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->vendorMenuGroupService->update($id, $validatedData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $vendorMenuGroup = $this->vendorMenuGroupService->get($id);

            $this->handlePermissionWithModel($vendorMenuGroup, Constants::deleteAbility);

            $dataArr = $this->vendorMenuGroupService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $vendorMenuGroup = $this->vendorMenuGroupService->get($id);

            $this->handlePermissionWithModel($vendorMenuGroup, Constants::editAbility);

            $status = $this->prepareStatusData($vendorMenuGroup);

            $this->vendorMenuGroupService->setStatus($id, $status);

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

        // manipulate blog data
        $relations = ['owner', 'editor'];
        $vendorMenuGroups = VendorMenuGroupWithKeyResource::collection($this->vendorMenuGroupService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorMenuGroup, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorMenuGroup' => 'create-vendorMenuGroup',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'menu_groups' => $vendorMenuGroups,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $vendorMenuGroup = $this->vendorMenuGroupService->get($id);
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorMenuGroup,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'menu_group' => $vendorMenuGroup,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareStatusData($vendorMenuGroup)
    {
        return $vendorMenuGroup->is_show_on_menu == Constants::publish
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
