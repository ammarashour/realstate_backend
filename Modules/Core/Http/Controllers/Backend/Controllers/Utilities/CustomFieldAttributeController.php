<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Utilities;

use App\Config\ps_constant;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Http\Requests\Utilities\StoreCustomFieldAttributeRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCustomFieldAttributeRequest;
use Modules\Core\Transformers\Backend\Model\Utilities\CustomFieldAttributeWithKeyResource;

class CustomFieldAttributeController extends PsController
{
    private const parentPath = 'utilities/custom_field_attribute';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = 'attribute.index';

    private const createRoute = 'attribute.create';

    private const editRoute = 'attribute.edit';

    public function __construct(
        protected CustomFieldAttributeServiceInterface $customFieldAttributeService,
        protected CustomFieldServiceInterface $customFieldService
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(CustomFieldAttribute::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create(Request $request)
    {
        // check permission start
        $this->handlePermissionWithModel(CustomFieldAttribute::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData($request);

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreCustomFieldAttributeRequest $request)
    {
        // para for route
        $params = [$request->route('table'), $request->route('field')];

        try {
            // Validate the request data
            $validData = $request->validated();

            // Save CustomFieldAttribute
            $this->customFieldAttributeService->save(customFieldAttributeData: $validData);

            // Success and Redirect
            return redirectView(routeName: self::indexRoute, parameter: $params);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage(), $params);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->route('attribute');

        // check permission start
        $blog = $this->customFieldAttributeService->get($id);
        $this->handlePermissionWithModel($blog, Constants::editAbility);

        $dataArr = $this->prepareEditData($request);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateCustomFieldAttributeRequest $request)
    {
        // para for route
        $editRoutePara = [
            $request->route('table'),
            $request->route('field'),
            $request->route('attribute'),
        ];

        $indexRoutePara = [
            $request->route('table'),
            $request->route('field'),
        ];

        try {
            $validatedData = $request->validated();

            $this->customFieldAttributeService->update(
                id: $request->route('attribute'),
                customFieldAttributeData: $validatedData,
            );

            return redirectView(self::indexRoute, parameter: $indexRoutePara);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $editRoutePara);
        }
    }

    public function destroy(Request $request)
    {
        // para for route
        $params = [$request->route('table'), $request->route('field')];

        try {
            $id = $request->route('attribute');
            $customFieldAttribute = $this->customFieldAttributeService->get($id);

            $this->handlePermissionWithModel($customFieldAttribute, Constants::deleteAbility);

            $dataArr = $this->customFieldAttributeService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag'], $params);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), null, $params);
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
            'order_by' => $request->input('sort_field') ?? '',
            'order_type' => $request->input('sort_order') ?? '',
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate customFieldAttribute data
        $coreKeysId = $request->route('field');
        $tableId = $request->route('table');

        $customField = $this->customFieldService->get(coreKeysId: $coreKeysId);
        $customFieldAttributes = CustomFieldAttributeWithKeyResource::collection($this->customFieldAttributeService->getAll(
            coreKeysId: $coreKeysId,
            noPagination: Constants::no,
            conds: $conds,
            pagPerPage: $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(ps_constant::customFieldAttribute, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createCustomFieldAttribute' => 'create-customFieldAttribute',
        ];

        $dataArr = [
            'tableId' => $tableId,
            'customizeHeader' => $customField,
            'customizeDetails' => $customFieldAttributes,
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

        return $dataArr;

    }

    private function prepareCreateData($request)
    {
        $coreKeysId = $request->route('field');
        $tableId = $request->route('table');
        $customField = $this->customFieldService->get(null, null, null, $coreKeysId);
        $dataArr = [
            'tableId' => $tableId,
            'customizeHeader' => $customField,
        ];

        return $dataArr;
    }

    private function prepareEditData($request)
    {
        // params
        $coreKeysId = $request->route('field');
        $tableId = $request->route('table');
        $id = $request->route('attribute');

        // manipulate data
        $customField = $this->customFieldService->get(coreKeysId: $coreKeysId);
        $customizeDetail = $this->customFieldAttributeService->get($id);

        $dataArr = [
            'tableId' => $tableId,
            'customizeHeader' => $customField,
            'customizeDetail' => $customizeDetail,
        ];

        return $dataArr;
    }

    //-------------------------------------------------------------------
    // Other
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
