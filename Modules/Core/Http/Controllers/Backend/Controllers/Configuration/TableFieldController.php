<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use App\Config\ps_constant;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Configuration\TableFieldServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Entities\Utilities\UiType;
use Modules\Core\Http\Requests\Utilities\StoreCustomFieldRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCoreFieldRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCustomFieldRequest;
use Modules\Core\Http\Services\TableService;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\Configuration\TableFieldWithKeyResource;

class TableFieldController extends PsController
{

    private const parentPath = 'configuration/table_field';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = 'tables.fields.index';

    private const createRoute = 'tables.fields.create';

    private const editRoute = 'tables.fields.edit';

    protected $successFlag;

    protected $dangerFlag;

    protected $csvFile;

    protected $warningFlag;

    public function __construct(
        protected TableService $tableService,
        protected LanguageServiceInterface $languageService,
        protected TableFieldServiceInterface $tableFieldService,
        protected CategoryServiceInterface $categoryService,
        protected CustomFieldServiceInterface $customFieldService,
        protected CoreFieldServiceInterface $coreFieldService
    ) {
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->csvFile = Constants::csvFile;
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission start
        $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);
        return renderView(self::indexPath, $dataArr);
    }

    public function create($table)
    {
        // check permission start
        $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::createPermission, Auth::id());

        $dataArr = $this->prepareCreateData($table);

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreCustomFieldRequest $request, $table)
    {
        $generatedData = $this->tableFieldService->generateCoreKeysId($table, $request->ui_type_id);

        if ($generatedData['flag'] !== 'success') {

            return redirect()->back()->with('status', $generatedData);
        }

        $checkCustomField = $this->customFieldService->get(coreKeysId: $generatedData['core_keys_id']);
        if (! empty($checkCustomField)) {
            $dataArr = [
                'flag' => 'warning',
                'isDupicate' => 1,
                'msg' => 'Custom field with this corekeysid is already exists',
            ];

            return redirect()->back()->with('status', $dataArr);
        }

        // para for route
        $params = [$request->route('table')];

        try {

            // Validate the request data
            $validData = $request->validated();

            // Save CustomField
            $this->tableFieldService->save(
                customFieldData: $validData,
                tableId: $table,
                generatedData: $generatedData
            );

            // Success and Redirect
            // return redirectView(routeName: self::indexRoute, parameter: $params);
            return redirectView(msg: __('custom_field_create_success'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage(), $params);
        }
    }

    public function edit($table, $id)
    {
        // check permission start
        $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

        $dataArr = $this->prepareEditData($id, $table);

        return renderView(self::editPath, $dataArr);
    }

    public function updateCoreField(UpdateCoreFieldRequest $request, $table, $id)
    {
        // para for route
        $editRoutePara = [$request->route('table'), $request->route('field')];
        $indexRoutePara = [$request->route('table')];

        try {

            // Validate the request data
            $validData = $request->validated();

            // update CoreField
            $this->tableFieldService->updateCoreField($id, $validData);

            // Success and Redirect
            // return redirectView(routeName: self::indexRoute, parameter: $indexRoutePara);
            return redirectView(msg: __('custom_field_updated_success'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $editRoutePara);
        }
    }

    public function updateCustomField(UpdateCustomFieldRequest $request, $table, $id)
    {
        // para for route
        $editRoutePara = [$request->route('table'), $request->route('field')];
        $indexRoutePara = [$request->route('table')];

        try {

            // Validate the request data
            $validData = $request->validated();

            // update customField
            $this->tableFieldService->updateCustomField($id, $validData);

            // Success and Redirect
            // return redirectView(routeName: self::indexRoute, parameter: $indexRoutePara);
            // return back()->withInput()->refresh();
            return redirectView(msg: __('custom_field_updated_success'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $editRoutePara);
        }
    }

    public function deleteCoreField(Request $request, $tableId, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::deletePermission, Auth::id());

            $dataArr = $this->tableFieldService->deleteCoreField($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag'], $indexRoutePara);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function deleteCustomField(Request $request, $tableId, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::deletePermission, Auth::id());

            $dataArr = $this->tableFieldService->deleteCustomField($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag'], $indexRoutePara);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function enableChangeCoreField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $coreField = $this->coreFieldService->get($id);

            $enable = $this->prepareEnableData($coreField);

            $this->tableFieldService->setCoreFieldEnable($id, $enable);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function enableChangeCustomField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $customField = $this->customFieldService->get($id);

            $enable = $this->prepareEnableData($customField);

            $this->tableFieldService->setCustomFieldEnable($id, $enable);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function isShowSortingChangeCoreField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $coreField = $this->coreFieldService->get($id);

            $isShowSorting = $this->prepareIsShowSortingData($coreField);

            $this->tableFieldService->setCoreFieldIsShowSorting($id, $isShowSorting);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function isShowSortingChangeCustomField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $customField = $this->customFieldService->get($id);

            $isShowSorting = $this->prepareIsShowSortingData($customField);

            $this->tableFieldService->setCustomFieldIsShowSorting($id, $isShowSorting);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function mandatoryChangeCoreField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $coreField = $this->coreFieldService->get($id);

            $mandatory = $this->prepareMandatoryData($coreField);

            $this->tableFieldService->setCoreFieldMandatory($id, $mandatory);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function mandatoryChangeCustomField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];

        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            $customField = $this->customFieldService->get($id);

            $mandatory = $this->prepareMandatoryData($customField);

            $this->tableFieldService->setCustomFieldMandatory($id, $mandatory);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function eyeStatusChangeCoreField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];
        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            // prepare Data
            $eyeStatus = $this->prepareEyeStatusData($request);

            // update
            $this->tableFieldService->updateEyeStatusCoreField($id, $eyeStatus);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function eyeStatusChangeCustomField(Request $request, $table, $id)
    {
        $indexRoutePara = [$request->route('table')];
        try {
            // check permission start
            $this->handlePermissionWithoutModel(Constants::tableFieldModule, ps_constant::updatePermission, Auth::id());

            // prepare Data
            $eyeStatus = $this->prepareEyeStatusData($request);

            // update
            $this->tableFieldService->updateEyeStatusCustomField($id, $eyeStatus);

            return redirectView(
                routeName: self::indexRoute,
                msg: __('core__be_status_updated'),
                parameter: $indexRoutePara
            );
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $indexRoutePara);
        }
    }

    public function screenDisplayUiStore(Request $request)
    {
        $indexRoutePara = [$request->route('table')];

        makeColumnHideShown($request);

        return redirectView(
            routeName: self::indexRoute,
            msg: __('core__be_status_updated'),
            parameter: $indexRoutePara
        );
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData($request)
    {

        // search filter
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $categoryId = $request->input('category_id');
        $tableId = $request->route('table');
        $symbol = $request->input('symbol') ?? 'en';

        $selectedTable = $this->tableService->getTable($tableId);
        $isItemTable = $selectedTable->core_key_type_id == 1 ? true : false;

        $customFieldRelations = ['uiTypeId'];

        $languageId = $this->languageService->get(conds: [Language::symbol => $symbol])->id;
        $tableFields = $this->tableFieldService->getAll($tableId, $languageId, $isItemTable, $categoryId, $conds);

        // categories
        $categories = $this->categoryService->getAll(noPagination: Constants::yes);
        $generalCoreFieldCounts = $this->coreFieldService->getAll(tableId: $tableId, isDel: Constants::unDelete, withNoPag: Constants::yes)->count();
        $generalCustomFieldCounts = $this->customFieldService->getAll(tableId: $tableId, isDelete: Constants::unDelete, withNoPag: Constants::yes, categoryId: 0)->count();
        $generalCategroies = $generalCoreFieldCounts + $generalCustomFieldCounts;
        foreach ($categories as $category) {
            $category->name = __($category->name);
            $category->count = $this->customFieldService->getAll(
                categoryId: $category->id,
                tableId: $tableId,
                isDelete: Constants::unDelete,
                withNoPag: Constants::yes,
                categoryIdOnly: Constants::yes
            )
                ->count();
        }

        // order by
        if (! empty($conds['order_by']) && ! empty($conds['order_type'])) {
            switch ($conds['order_by']) {
                case 'show_in_table':
                    $conds['order_by'] = 'is_include_in_hideshow';
                    break;
                case 'attribute':
                    $conds['order_by'] = 'is_core_field';
                    break;
                case 'enable':
                    $conds['order_by'] = 'enable';
                    break;
            }
            $tableFields = $tableFields->orderBy($conds['order_by'], $conds['order_type']);
        } else {
            $tableFields = $tableFields->orderBy('enable', 'desc')
                ->orderBy('name', 'asc');
        }

        $tableFields = $tableFields->paginate($row);

        $fields = TableFieldWithKeyResource::collection($tableFields);

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::tableField, $this->controlFieldArr());

        $dataArr = [
            'selectedTable' => $selectedTable,
            'tableId' => $tableId,
            'fields' => $fields,
            'search' => $conds['searchterm'],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'categories' => $categories,
            'categoryId' => $categoryId ?? '',
            'selectedTable' => $selectedTable,
            'generalCategroies' => $generalCategroies,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
        ];

        return $dataArr;
    }

    private function prepareCreateData($tableId)
    {
        $uiTypes = UiType::all();
        $table = $this->tableService->getTable($tableId);
        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(null, Constants::publish));

        $dataArr = [
            'selectedClientTable' => $table,
            'uiTypes' => $uiTypes,
            'categories' => $categories,
        ];

        return $dataArr;
    }

    private function prepareEditData($id, $tableId)
    {
        $field = $this->customFieldService->get($id);
        $uiTypes = UiType::all();
        $table = $this->tableService->getTable($tableId);
        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(null, Constants::publish));

        if (! $field) {
            $field = $this->coreFieldService->get($id);
            $field->name = $field->label_name;
        }
        $dataArr = [
            'clientCustomField' => $field,
            'selectedClientTable' => $table,
            'uiTypes' => $uiTypes,
            'categories' => $categories,
        ];

        return $dataArr;
    }

    private function prepareEnableData($field)
    {
        return $field->enable == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareIsShowSortingData($field)
    {
        return $field->is_show_sorting == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareMandatoryData($field)
    {
        return $field->mandatory == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareEyeStatusData($eyeStatusData)
    {
        return [
            'id' => $eyeStatusData->id,
            'is_include_in_hideshow' => $eyeStatusData->isIncluded,
            'is_show' => $eyeStatusData->isShow,
            'is_show_in_filter' => $eyeStatusData->isShowInFilter,
        ];
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
