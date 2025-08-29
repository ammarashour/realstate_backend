<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Menu;

use App\Config\ps_constant;
use App\Http\Contracts\Menu\ModuleServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Http\Requests\Menu\StoreModuleRequest;
use Modules\Core\Http\Requests\Menu\UpdateModuleRequest;
use Modules\Core\Transformers\Backend\Model\Menu\ModuleWithKeyResource;

class ModuleController extends PsController
{
    private const parentPath = 'module';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(protected ModuleServiceInterface $moduleService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Module::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(Module::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreModuleRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Menu Group
            $this->moduleService->save(moduleData : $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $module = $this->moduleService->get($id);

        $this->handlePermissionWithModel($module, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateModuleRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->moduleService->update(
                id : $id,
                moduleData : $validatedData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $module = $this->moduleService->get($id);

            $this->handlePermissionWithModel($module, Constants::deleteAbility);

            $dataArr = $this->moduleService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $module = $this->moduleService->get($id);

            $this->handlePermissionWithModel($module, Constants::editAbility);

            $status = $this->prepareStatusData($module);

            $this->moduleService->setStatus($id, $status);

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

        $modules = ModuleWithKeyResource::collection($this->moduleService->getAll(relation : $relations,
            pagPerPage : $row,
            conds : $conds,
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::module, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createModule' => 'create-module',
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
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::module,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::module,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );
        $module = $this->moduleService->get($id);

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
