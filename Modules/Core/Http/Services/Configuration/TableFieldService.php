<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\ps_constant;
use App\Config\ps_url;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Configuration\TableFieldServiceInterface;
use App\Http\Contracts\Localization\BeLanguageStringServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\DynamicColumnVisibilityServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreKeyCounter;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Table;
use Modules\Core\Http\Services\CoreKeyCounterService;
use Modules\Core\Http\Services\CoreKeyTypeService;
use Modules\Core\Http\Services\ProjectService;
use Modules\Core\Http\Services\TableService;

class TableFieldService extends PsService implements TableFieldServiceInterface
{
    protected $languageSymbolCol;

    protected $unDelete;

    protected $coreFieldFilterModuleNameCol;

    public function __construct(
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldServiceInterface $customFieldService,
        protected CategoryServiceInterface $categoryService,
        protected ProjectService $projectService,
        protected BeLanguageStringServiceInterface $languageStringService,
        protected DynamicColumnVisibilityServiceInterface $dynamicColumnVisibilityService,
        protected CoreKeyCounterService $coreKeyCounterService,
        protected TableService $tableService,
        protected CoreKeyTypeService $coreKeyTypeService
    ) {}

    public function save($customFieldData, $tableId, $generatedData)
    {
        DB::beginTransaction();

        try {
            // get project Data
            $project = $this->projectService->getProject();

            // save customField Data
            $customField = $this->saveCustomField($customFieldData, $generatedData, $project, $tableId);

            // save or update in client coreKeyCounter Data
            $this->coreKeyCounterService->saveOrUpdate($generatedData['core_keys_id'], $generatedData['module_name']);

            // save or update DynamicColumnVisibility Data
            $dynamicColumnVisibilityData = $this->prepareUpdateOrCreateDynamicColumnVisibilityData($customField, $customField->core_keys_id);
            $this->dynamicColumnVisibilityService->updateOrCreate($dynamicColumnVisibilityData['dataArrWhere'], $dynamicColumnVisibilityData['data']);

            // save languageString Data For name
            $nameLangData = $this->prepareSaveLanguageStringData($customFieldData, 'nameForm', $generatedData['name_key']);
            $this->languageStringService->updateOrInsert($nameLangData->values);

            // save languageString Data For placeholder
            $nameLangData = $this->prepareSaveLanguageStringData($customFieldData, 'placeholderForm', $generatedData['placeholder_key']);
            $this->languageStringService->updateOrInsert($nameLangData->values);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCoreField($id)
    {
        try {

            $name = '';
            $name = $this->coreFieldService->delete($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => __($name)]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteCustomField($id)
    {
        try {
            $name = '';
            $name = $this->customFieldService->delete($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => __($name)]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getAll($tableId, $languageId, $isItemTable, $categoryId, $conds)
    {
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $customFields = DB::table(CustomField::tableName)
                ->where(CustomField::tableId, $tableId)
                ->where(CustomField::isDelete, 0)
                ->join(LanguageString::tableName, function ($join) use ($conds, $languageId) {
                    $join->on(CustomField::tableName . '.' . CustomField::name, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%')
                        ->orOn(CustomField::tableName . '.' . CustomField::placeholder, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->select(LanguageString::tableName . '.' . LanguageString::value . ' as placeholder', CustomField::name . ' as nameKey', CustomField::placeholder . ' as placeholderKey', LanguageString::value . ' as name', CustomField::tableName . '.' . CustomField::id, CustomField::tableName . '.' . CustomField::addedDate, CustomField::tableName . '.' . CustomField::addedUserId, CustomField::tableName . '.' . CustomField::updatedDate, CustomField::tableName . '.' . CustomField::updatedUserId, CustomField::tableName . '.' . CustomField::updatedFlag, DB::raw('ui_type_id,null as field_name,core_keys_id, mandatory,enable, is_delete, module_name, data_type, table_id, project_id, project_name,base_module_name,is_include_in_hideshow,is_show,is_core_field,permission_for_enable_disable,permission_for_delete,permission_for_mandatory,is_show_sorting,ordering,is_show_in_filter'))
                ->groupBy(CustomField::tableName . '.' . CustomField::id)
                ->when($isItemTable, function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });

            $coreFields = DB::table(CoreField::tableName)
                ->where(CoreField::tableId, $tableId)
                ->where(CoreField::isDelete, 0)
                ->join(LanguageString::tableName, function ($join) use ($conds, $languageId) {
                    $join->on(CoreField::tableName . '.' . CoreField::placeholder, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%')
                        ->orOn(CoreField::tableName . '.' . CoreField::labelName, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->select(
                    LanguageString::tableName . '.' . LanguageString::value . ' as placeholder',
                    CoreField::labelName . ' as nameKey',
                    CoreField::placeholder . ' as placeholderKey',
                    LanguageString::value . ' as name',
                    CoreField::tableName . '.' . CoreField::id,
                    CoreField::tableName . '.' . CoreField::addedDate,
                    CoreField::tableName . '.' . CoreField::addedUserId,
                    CoreField::tableName . '.' . CoreField::updatedDate,
                    CoreField::tableName . '.' . CoreField::updatedUserId,
                    CoreField::tableName . '.' . CoreField::updatedFlag,
                    DB::raw('null as ui_type_id,field_name, null as core_keys_id, mandatory,enable, is_delete, module_name, data_type, table_id, project_id, project_name,base_module_name,is_include_in_hideshow,is_show,is_core_field,permission_for_enable_disable,permission_for_delete,permission_for_mandatory,is_show_sorting,ordering,is_show_in_filter')
                )
                ->groupBy(CoreField::tableName . '.' . CoreField::id);
        } else {
            $customFields = DB::table(CustomField::tableName)
                ->where(CustomField::tableId, $tableId)
                ->where(CustomField::isDelete, 0)
                ->when($isItemTable, function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->leftJoin(LanguageString::tableName, function ($join) use ($conds, $languageId) {
                    $join->on(CustomField::tableName . '.' . CustomField::placeholder, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->leftJoin(LanguageString::tableName . ' as lang', function ($join) use ($conds, $languageId) {
                    $join->on(CustomField::tableName . '.' . CustomField::name, '=', 'lang.' . LanguageString::key)
                        ->where('lang.' . LanguageString::languageId, '=', $languageId)
                        ->where('lang.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->select(LanguageString::tableName . '.' . LanguageString::value . ' as placeholder', CustomField::name . ' as nameKey', CustomField::placeholder . ' as placeholderKey', 'lang.' . LanguageString::value . ' as name', CustomField::tableName . '.' . CustomField::id, CustomField::tableName . '.' . CustomField::addedDate, CustomField::tableName . '.' . CustomField::addedUserId, CustomField::tableName . '.' . CustomField::updatedDate, CustomField::tableName . '.' . CustomField::updatedUserId, CustomField::tableName . '.' . CustomField::updatedFlag, DB::raw('ui_type_id,null as field_name,core_keys_id, mandatory,enable, is_delete, module_name, data_type, table_id, project_id, project_name,base_module_name,is_include_in_hideshow,is_show,is_core_field,permission_for_enable_disable,permission_for_delete,permission_for_mandatory,is_show_sorting,ordering,is_show_in_filter'))
                ->groupBy('psx_customize_ui.id');

            $coreFields = DB::table(CoreField::tableName)
                ->where(CoreField::tableId, $tableId)
                ->where(CoreField::isDelete, 0)
                ->leftJoin(LanguageString::tableName, function ($join) use ($conds, $languageId) {
                    $join->on(CoreField::tableName . '.' . CoreField::placeholder, '=', LanguageString::tableName . '.' . LanguageString::key)
                        ->where(LanguageString::tableName . '.' . LanguageString::languageId, '=', $languageId)
                        ->where(LanguageString::tableName . '.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->leftJoin(LanguageString::tableName . ' as lang', function ($join) use ($conds, $languageId) {
                    $join->on(CoreField::tableName . '.' . CoreField::labelName, '=', 'lang.' . LanguageString::key)
                        ->where('lang.' . LanguageString::languageId, '=', $languageId)
                        ->where('lang.' . LanguageString::value, 'like', '%' . $conds['searchterm'] . '%');
                })
                ->select(
                    LanguageString::tableName . '.' . LanguageString::value . ' as placeholder',
                    CoreField::labelName . ' as nameKey',
                    CoreField::placeholder . ' as placeholderKey',
                    'lang.' . LanguageString::value . ' as name',
                    CoreField::tableName . '.' . CoreField::id,
                    CoreField::tableName . '.' . CoreField::addedDate,
                    CoreField::tableName . '.' . CoreField::addedUserId,
                    CoreField::tableName . '.' . CoreField::updatedDate,
                    CoreField::tableName . '.' . CoreField::updatedUserId,
                    CoreField::tableName . '.' . CoreField::updatedFlag,
                    DB::raw('null as ui_type_id,field_name, null as core_keys_id, mandatory,enable, is_delete, module_name, data_type, table_id, project_id, project_name,base_module_name,is_include_in_hideshow,is_show,is_core_field,permission_for_enable_disable,permission_for_delete,permission_for_mandatory,is_show_sorting,ordering,is_show_in_filter')
                )
                ->groupBy(CoreField::tableName . '.' . CoreField::id);
        }

        if (isset($categoryId)) {
            $tableFields = $customFields;
        } else {
            $tableFields = $customFields->unionAll($coreFields);
        }

        return $tableFields;
    }

    public function generateCoreKeysId($tableId, $ui_type_id)
    {
        try{
            $project = $this->projectService->getProject();
            if(empty($project)) {
                return ['msg' => __('base_proj_not_found'), 'flag' =>  'error'];
            }

            $coreKeyTypeId = $this->tableService->getTable($tableId)->core_key_type_id;
            $clientCode = $this->coreKeyTypeService->getCoreKeyType($coreKeyTypeId)->client_code;
            $coreKeysCounter = $this->coreKeyCounterService->getCoreKeyCounter(conds: [CoreKeyCounter::code => $clientCode]);

            $dataArr = $this->coreKeyCounterService->corekeyGenerateClient($coreKeysCounter['code'], $coreKeysCounter['counter']);
            $dataArr['data_type'] = $this->coreKeyCounterService->decisionForCustomFieldDataType($ui_type_id);
            $dataArr['flag'] = 'success';

            return $dataArr;

        } catch (\Throwable $e) {
            return ['msg' =>  $e->getMessage() . $e->getLine(), 'flag' =>  'error'];
        }
    }

    public function updateCoreField($id, $coreFieldData)
    {
        DB::beginTransaction();

        try {

            // update CoreField Data
            $coreField = $this->coreFieldService->update($id, $coreFieldData);

            // handle DynamicColumnVisibility Data
            $this->handelDynamicColumnVisibilityData($coreField, $coreField->field_name);

            // update languageString Data For name
            $nameLangData = $this->prepareSaveLanguageStringData($coreFieldData, 'nameForm', $coreFieldData['name']);
            $this->languageStringService->updateOrInsert($nameLangData->values);

            // update languageString Data For placeholder
            $placeholderLangData = $this->prepareSaveLanguageStringData($coreFieldData, 'placeholderForm', $coreFieldData['placeholder']);
            $this->languageStringService->updateOrInsert($placeholderLangData->values);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function updateCustomField($id, $customFieldData)
    {
        DB::beginTransaction();

        try {
            // update customField Data
            $customField = $this->customFieldService->update($id, $customFieldData);

            // handle DynamicColumnVisibility Data
            $this->handelDynamicColumnVisibilityData($customField, $customField->core_keys_id);

            // update languageString Data For name
            $nameLangData = $this->prepareSaveLanguageStringData($customFieldData, 'nameForm', $customFieldData['name']);
            $this->languageStringService->updateOrInsert($nameLangData->values);

            // update languageString Data For placeholder
            $placeholderLangData = $this->prepareSaveLanguageStringData($customFieldData, 'placeholderForm', $customFieldData['placeholder']);
            $this->languageStringService->updateOrInsert($placeholderLangData->values);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function setCoreFieldEnable($id, $enable)
    {
        try {
            $enable = $this->prepareUpdateEnableData($enable);

            return $this->coreFieldService->update($id, $enable);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setCustomFieldEnable($id, $enable)
    {
        try {
            $enable = $this->prepareUpdateEnableData($enable);

            return $this->customFieldService->update($id, $enable);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setCoreFieldIsShowSorting($id, $isShowSorting)
    {
        try {
            $isShowSorting = $this->prepareUpdateIsShowSortingData($isShowSorting);

            return $this->coreFieldService->update($id, $isShowSorting);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setCustomFieldIsShowSorting($id, $isShowSorting)
    {
        try {
            $isShowSorting = $this->prepareUpdateIsShowSortingData($isShowSorting);

            return $this->customFieldService->update($id, $isShowSorting);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setCoreFieldMandatory($id, $mandatory)
    {
        try {
            $mandatory = $this->prepareUpdateMandatoryData($mandatory);

            return $this->coreFieldService->update($id, $mandatory);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setCustomFieldMandatory($id, $mandatory)
    {
        try {
            $mandatory = $this->prepareUpdateMandatoryData($mandatory);

            return $this->customFieldService->update($id, $mandatory);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function updateEyeStatusCoreField($id, $eyeStatusData)
    {
        try {
            // update
            $coreField = $this->coreFieldService->update($id, $eyeStatusData);

            // handle DynamicColumnVisibility Data
            $this->handelDynamicColumnVisibilityData($coreField, $coreField->field_name);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function updateEyeStatusCustomField($id, $eyeStatusData)
    {
        try {
            // update
            $customField = $this->customFieldService->update($id, $eyeStatusData);

            // handle DynamicColumnVisibility Data
            $this->handelDynamicColumnVisibilityData($customField, $customField->core_keys_id);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------
    private function prepareSaveLanguageStringData($fieldData, $para, $keyId)
    {
        $langData = new \stdClass();

        foreach ($fieldData[$para] as $key => $value) {
            $langData->$key = $value;
        }
        if (empty($fieldData[$para]['values'])) {
            $langData->values = [];
        }

        for($i = 0; $i < count($langData->values); $i++){
            $langData->values[$i]['key'] = $keyId;
            $langData->values[$i]['is_from_builder'] = 0;

            // remove key called symbol in $langData->values
            unset($langData->values[$i]['symbol']);
            unset($langData->values[$i]['id']);
        }

        return $langData;
    }

    private function prepareUpdateEnableData($enable)
    {
        return ['enable' => $enable];
    }

    private function prepareUpdateIsShowSortingData($isShowSorting)
    {
        return ['is_show_sorting' => $isShowSorting];
    }

    private function prepareUpdateMandatoryData($mandatory)
    {
        return ['mandatory' => $mandatory];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCustomField($customFieldData, $generatedData, $project, $tableId)
    {
        $customField = new CustomField();
        $customField->fill($customFieldData);
        $customField->name = $generatedData['name_key'];
        $customField->placeholder = $generatedData['placeholder_key'];
        $customField->core_keys_id = $generatedData['core_keys_id'];
        $customField->module_name = $generatedData['module_name'];
        $customField->data_type = $generatedData['data_type'];
        $customField->table_id = $tableId;
        $customField->project_id = $project->id;
        $customField->project_name = $project->project_name;
        $customField->base_module_name = $generatedData['base_module_name'];
        $customField->is_core_field = '0';
        $customField->permission_for_enable_disable = '1';
        $customField->permission_for_delete = '1';
        $customField->permission_for_mandatory = '1';
        $customField->added_user_id = Auth::id();
        $customField->save();

        return $customField;
    }

    private function prepareUpdateOrCreateDynamicColumnVisibilityData($field, $key)
    {
        $dataArrWhere = [
            'module_name' => $field->module_name,
            'key' => $key,
        ];

        $data = (object) [
            'module_name' => $field->module_name,
            'key' => $key,
            'is_show' => $field->is_show,
        ];

        return [
            'dataArrWhere' => $dataArrWhere,
            'data' => $data,
        ];
    }

    private function handelDynamicColumnVisibilityData($field, $key)
    {
        $isIncludeInHideShow = $field->is_include_in_hideshow;
        $dynamicColumnVisibility = $this->dynamicColumnVisibilityService->get(null, $key, $field->module_name);

        $dynamicColumnVisibilityData = $this->prepareUpdateOrCreateDynamicColumnVisibilityData($field, $key);
        $this->dynamicColumnVisibilityService->updateOrCreate($dynamicColumnVisibilityData['dataArrWhere'], $dynamicColumnVisibilityData['data']);

        if (! empty($dynamicColumnVisibility) && empty($isIncludeInHideShow)) {
            $dynamicColumnVisibility->delete();
        }
    }
}
