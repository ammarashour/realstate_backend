<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\VendorModuleServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\VendorModule;
use Modules\Core\Http\Requests\Menu\StoreVendorModuleRequest;
use Modules\Core\Http\Requests\Menu\UpdateVendorModuleRequest;
use Modules\Core\Transformers\Backend\Model\VendorModule\VendorModuleWithKeyResource;

class VendorModuleController extends PsController
{
    private const parentPath = 'vendor_module/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const indexRoute = 'vendor_module_registering.index';

    private const createRoute = 'vendor_module_registering.create';

    private const editRoute = 'vendor_module_registering.edit';

    public function __construct(
        protected VendorModuleServiceInterface $vendorModuleService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(VendorModule::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(VendorModule::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    /**
     * Manually migrate vendor modules and does not use store function
     */
    public function store(StoreVendorModuleRequest $request)
    {
        try {
            $validData = $request->validated();

            $this->vendorModuleService->save(vendorModuleData : $validData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $module = $this->vendorModuleService->get($id);

        $this->handlePermissionWithModel($module, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorModuleRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->vendorModuleService->update(
                id : $id,
                vendorModuleData : $validatedData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $module = $this->vendorModuleService->get($id);

            $this->handlePermissionWithModel($module, Constants::deleteAbility);

            $dataArr = $this->vendorModuleService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {
            $module = $this->vendorModuleService->get($id);

            $this->handlePermissionWithModel($module, Constants::editAbility);

            $status = $this->prepareStatusData($module);

            $this->vendorModuleService->setStatus($id, $status);

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

        $modules = VendorModuleWithKeyResource::collection($this->vendorModuleService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds,
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendorModuleKey, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createVendorModule' => 'create-vendorModule',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'modules' => $modules,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

    }

    private function prepareCreateData()
    {
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorModuleKey,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $module = $this->vendorModuleService->get($id);
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorModuleKey,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'module' => $module,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareStatusData($module)
    {
        return $module->status == Constants::publish
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
