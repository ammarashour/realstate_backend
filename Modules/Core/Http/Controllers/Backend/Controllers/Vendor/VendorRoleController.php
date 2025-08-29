<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\ModuleServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Authorization\Permission;
use Modules\Core\Entities\Vendor\VendorRole;
use Modules\Core\Entities\Vendor\VendorRolePermission;
use Modules\Core\Http\Requests\Vendor\StoreVendorRoleRequest;
use Modules\Core\Http\Requests\Vendor\UpdateVendorRoleRequest;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorRoleWithKeyResource;
use App\Http\Contracts\Vendor\VendorRoleServiceInterface;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;

class VendorRoleController extends PsController
{
    private const parentPath = 'vendor_role/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const indexRoute = 'vendor_role.index';

    private const createRoute = 'vendor_role.create';

    private const editRoute = 'vendor_role.edit';

    public function __construct(protected VendorRoleServiceInterface $roleService,
        protected VendorModuleServiceInterface $moduleService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorRole::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $this->handlePermissionWithModel(VendorRole::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreVendorRoleRequest $request)
    {
        try {
        
            $validateData = $request->validated();

            $this->roleService->save(roleData : $validateData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $vendorRole = $this->roleService->get($id);

        $this->handlePermissionWithModel($vendorRole, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorRoleRequest $request, $id)
    {
        try {

            $validateData = $request->validated();

            $this->roleService->update(
                id : $id,
                roleData : $validateData,
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    public function destroy($id)
    {
        try {
            $role = $this->roleService->get($id);

            $this->handlePermissionWithModel($role, Constants::deleteAbility);

            $dataArr = $this->roleService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $role = $this->roleService->get($id);

            $this->handlePermissionWithModel($role, Constants::editAbility);

            $status = $this->prepareStatusData($role);

            $this->roleService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
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
        $relations = ['editor', 'owner'];
        $roles = VendorRoleWithKeyResource::collection($this->roleService->getAll($relations, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorRole, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorRole' => 'create-vendorRole',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'roles' => $roles,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {

        $modules = $this->moduleService->getAll(null, null, null, 1);
        $permissions = Permission::latest()->get();

        $coreFieldFilterSettings = $this->coreFieldService->getAll(withNoPag: 1, code: Constants::vendorRole);

        return [
            'modules' => $modules,
            'permissions' => $permissions,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $role = VendorRole::find($id);

        $rolePermissions = VendorRolePermission::where(VendorRolePermission::vendorRoleId, $id)->get();

        $modules = $this->moduleService->getAll();
        $permissions = Permission::latest()->get();
        $coreFieldFilterSettings = $this->coreFieldService->getAll(withNoPag: 1, code: Constants::vendorRole);

        return [
            'role' => $role,
            'rolePermissions' => $rolePermissions,
            'modules' => $modules,
            'permissions' => $permissions,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
    }

    private function prepareStatusData($role)
    {
        return $role['status'] == Constants::publish
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
