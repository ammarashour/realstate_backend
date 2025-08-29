<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Project;
use App\Config\ps_constant;
use App\Config\ps_url;
use App\Enums\Language\InsertionSource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\CoreKeyCounter;
use Modules\Core\Entities\CoreKeyType;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Table;
use App\Http\Contracts\Localization\BeLanguageStringServiceInterface;
use App\Http\Contracts\Configuration\BuilderSettingServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use Exception;
use Modules\Core\Http\Facades\LanguageFacade;

class BuilderSettingService extends PsService implements BuilderSettingServiceInterface
{

    public function __construct(
        protected BeLanguageStringServiceInterface $beLanguageStringService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldServiceInterface $customFieldService
    ) {}

    public function get($id = null)
    {
        $project = Project::when($id, function ($q, $id) {
            $q->where('id', $id);
        })->first();

        return $project;
    }

    public function update($id, $builderSettingData)
    {
        DB::beginTransaction();
        try {

            $this->updateBuilderSetting($id, $builderSettingData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function handleProjectReset()
    {
        // Check builder connection
        $checkBuilderConnection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);

        if ($this->isConnectionFailed($checkBuilderConnection)) {
            return [
                "flag" => 'error',
                "msg"  => $checkBuilderConnection->message ?? __('connection__failed')
            ];
        }

        // Sync project data from external source
        $sync_project = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getNextProjectJson, "&api_key=" . getApiKey());

        //Truncate tables to reset project data
        $this->truncateProjectTables();

        // Handle core key counters
        $this->resetCoreKeyCounters();

        // Delete client custom fields
        deleteDataOfClientCustomFields("ps-");

        // Prepare data for insertion
        $project = $sync_project->project;
        $customFields = $sync_project->custom_field_infos;
        $coreFields = $sync_project->core_field_infos;
        $tables = $sync_project->tables;
        $coreKeyTypes = $sync_project->core_key_types;
        $languageStrings = [];
        
        DB::beginTransaction();
        try {
            // Save project details
            $this->saveProject($project);

            // Save custom and core fields
            $this->saveFields($customFields, $project, false);
            $this->saveFields($coreFields, $project, true);
            // Save tables
            $this->saveTables($tables, $project);

            DB::commit();

            return [
                "flag" => 'success',
                "msg"  => "Project Reset Success"
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                "flag" => 'error',
                "msg"  => $e->getMessage()
            ];
        }
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function updateBuilderSetting($id, $builderSettingData)
    {
        $builder_setting = Project::find($id);
        $builder_setting->project_url = $builderSettingData->project_url;
        $builder_setting->token = $builderSettingData->token;
        $builder_setting->update();

        $oldBuilderDomain = ps_constant::builderDomain;
        $newBuilderDomain = $builderSettingData->builder_url;

        //update builder domain in ps_constant
        $file = '../app/Config/ps_constant.php';
        $code = file_get_contents($file);
        $new_const = str_replace('const builderDomain = "' . $oldBuilderDomain . '";', 'const builderDomain = "' . $newBuilderDomain . '";', $code);
        file_put_contents($file, $new_const);
    }

    /**
     * Save or update project data in the projects table.
     */
    private function saveProject($project)
    {
        Project::updateOrCreate(
            ['base_project_id' => $project->base_project_id],
            [
                'id'              => $project->id,
                'project_name'    => $project->project_name,
                'project_code'    => $project->project_code,
                'project_url'     => $project->project_url,
                'first_time_sync' => 1,
                'added_user_id'   => Auth::id()
            ]
        );
    }


    /**
     * Fill the field data for custom or core fields.
     */
    private function fillCoreFieldData($field, $project, $isCoreField)
    {
        
        $fieldModelData = [
            "id" => $field->id,
            "table_id" => $field->table_id,
            "project_name" => $project->project_name,
            "project_id" => $project->id,
            "label_name" => $field->name_key,
            "module_name" => $field->module_name,
            "base_module_name" => $field->base_module_name,
            "field_name" => $field->field_name,
            "placeholder" => $field->placeholder_key,
            "data_type" => $field->data_type,
            "is_delete" => $field->is_delete,
            "enable" => $field->enable,
            "mandatory" => $field->mandatory,
            "is_show_sorting" => $field->is_show_sorting,
            "ordering" => $field->ordering,
            "is_show_in_filter" => $field->is_show_in_filter,
            "is_include_in_hideshow" => $field->is_include_in_hideshow,
            "is_show" => $field->is_show,
            "is_core_field" => $isCoreField ? 1 : 0,
            "permission_for_enable_disable" => $field->permission_for_enable_disable,
            "permission_for_delete" => $field->permission_for_delete,
            "permission_for_mandatory" => $field->permission_for_mandatory,
        ];

        $this->coreFieldService->save((object) $fieldModelData);
        
    }
    private function fillCustomFieldData($field, $project)
    {
        $fieldModel = new CustomField();
        $fieldModel->id                 = $field->id;
        $fieldModel->table_id           = $field->table_id;
        $fieldModel->project_name       = $project->project_name;
        $fieldModel->project_id         = $project->id;
        $fieldModel->module_name        = $field->module_name;
        $fieldModel->base_module_name   = $field->base_module_name;
        $fieldModel->placeholder        = $field->placeholder_key;
        $fieldModel->data_type          = $field->data_type;
        $fieldModel->is_delete          = $field->is_delete;
        $fieldModel->enable             = $field->enable;
        $fieldModel->mandatory          = $field->mandatory;
        $fieldModel->is_show_sorting    = $field->is_show_sorting;
        $fieldModel->ordering           = $field->ordering;
        $fieldModel->is_show_in_filter  = $field->is_show_in_filter;
        $fieldModel->is_include_in_hideshow = $field->is_include_in_hideshow;
        $fieldModel->is_show            = $field->is_show;
        $fieldModel->is_core_field      = 0;
        $fieldModel->permission_for_enable_disable = $field->permission_for_enable_disable;
        $fieldModel->permission_for_delete = $field->permission_for_delete;
        $fieldModel->permission_for_mandatory = $field->permission_for_mandatory;
        $fieldModel->added_user_id      = Auth::id();

        $fieldModel->save();
    }

    /**
     * Save custom and core fields.
     */
    private function saveFields($fields, $project, $isCoreField)
    {
        $languages = LanguageFacade::getAll();

        // Remove languages of all core and custom fields
        $this->beLanguageStringService->deleteByIsFromBuilderFlag(1);
        $languageStrings = [];
        foreach ($fields as $field) {
            //$this->addLanguageStrings($languages, $field);
            $languageStrings[] = $this->prepareLangStringData($field);
            $languageStrings[] = $this->prepareLangStringPlaceholderData($field);
            
            if ($isCoreField) {
                $this->fillCoreFieldData($field, $project, $isCoreField);
            } else {
                $this->fillCustomFieldData($field, $project);
            }
            if ($field->is_include_in_hideshow == 1) {
                $this->saveDynamicColumnVisibility($field, $isCoreField);
            }
        }

        // Import langauge strings
        $this->beLanguageStringService->importLanguageStrings(
            toLanguages: $languages, 
            langStrings: $languageStrings, 
            prefix: '',
            insertionSource: InsertionSource::FROM_BUILDER
        );

        // update language json files
        // this function will generate updated backend json files.
        $this->beLanguageStringService->generateJsonFiles('');
    }

    /**
     * Save dynamic column visibility settings.
     */
    private function saveDynamicColumnVisibility($field, $isCoreField)
    {
        $visibility = new DynamicColumnVisibility();
        $visibility->module_name = $field->module_name;
        $visibility->key = $isCoreField ? $field->field_name : $field->core_keys_id;
        $visibility->is_show = $field->is_show;
        $visibility->added_user_id = Auth::id();
        $visibility->save();
    }

    
    /**
     * @deprecated
     * Add language strings for the field.
     */
    private function addLanguageStrings($languages, $field)
    {
        // Creating "Name" Language String
        // No Target Language Required
        // - Because we need to copy to all languages
        // - Builder will only give english name and we will copy to all languages
        // No Prefix Required
        // - We don't have to add prefix for language strings from builder
        // - This is the reason, we are passing empty string as prefix
        // - But the prefix will required all other cases, expect this one        
        $langStringData = $this->prepareLangStringData($field);
        $this->beLanguageStringService->importLanguageStrings(
                            toLanguages: $languages, 
                            langStrings: $langStringData, 
                            prefix: '',
                            insertionSource: InsertionSource::FROM_BUILDER
                        );

        // Creating "Name Placeholder" Language String
        // No Target Language Required
        // - Because we need to copy to all languages
        // - Builder will only give english name and we will copy to all languages
        // No Prefix Required
        // - We don't have to add prefix for language strings from builder
        // - This is the reason, we are passing empty string as prefix
        // - But the prefix will required all other cases, expect this one
        $langStringPlaceholderData = $this->prepareLangStringPlaceholderData($field);
        $this->beLanguageStringService->importLanguageStrings(
                            toLanguages: $languages, 
                            langStrings: $langStringPlaceholderData, 
                            prefix: '',
                            insertionSource: InsertionSource::FROM_BUILDER
                        );
    }

    private function prepareLangStringData($field)
    {
        return [
            "key" => $field->name_key,
            "value" => $field->name,
            "is_from_builder" => 1,
            "language_id" => 1,
        ];
    }
    private function prepareLangStringPlaceholderData($field)
    {
        return [
            "key" => $field->placeholder_key,
            "value" => $field->placeholder,
            "is_from_builder" => 1,
            "language_id" => 1,
        ];
    }

    /**
     * Save tables related to the project.
     */
    private function saveTables($tables, $project)
    {
        foreach ($tables as $tableObj) {
            $table = new Table();
            $table->id = $tableObj->id;
            $table->name = $tableObj->name;
            $table->description = $tableObj->description;
            $table->core_key_type_id = $tableObj->core_key_type_id;
            $table->is_only_for_core_field = $tableObj->is_only_for_core_field;
            $table->table_used_type_id = $tableObj->table_used_type_id;
            $table->added_user_id = Auth::id();
            $table->save();
        }
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------

    /**
     * Check if the connection to the builder failed.
     */
    private function isConnectionFailed($connection)
    {
        return empty($connection) || $connection->status !== "success";
    }

    /**
     * Truncate necessary tables for project reset.
     */
    private function truncateProjectTables()
    {
        Table::truncate();
        CustomField::truncate();
        CoreField::truncate();
        DynamicColumnVisibility::truncate();
    }

    /**
     * Reset the core key counters, except for 'pmt'.
     */
    private function resetCoreKeyCounters()
    {
        // Delete all data except 'pmt' from psx_core_key_counters
        $coreKeyCounterIds = CoreKeyCounter::where("code", "!=", "pmt")->pluck("id");
        CoreKeyCounter::destroy($coreKeyCounterIds);

        // Reinitialize core key counters
        $coreKeyTypes = CoreKeyType::where("client_code", "!=", "pmt")->get();
        foreach ($coreKeyTypes as $coreKeyType) {
            $newCounter = new CoreKeyCounter();
            $newCounter->code = $coreKeyType->client_code;
            $newCounter->counter = 1;
            $newCounter->added_user_id = '1';
            $newCounter->save();
        }
    }
}
