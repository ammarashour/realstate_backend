<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_config;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Utilities\ActivatedFileName;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Table;
use Modules\Core\Entities\TableUsedType;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreKeyType;
use Modules\Core\Entities\UpdaterData;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\CoreKeyCounter;
use Modules\Core\Transformers\Backend\Model\Table\TableWithKeyResource;
use App\Config\ps_constant;
use App\Config\ps_url;
use Modules\Core\Http\Services\Localization\LanguageService;
use Modules\Core\Http\Services\Localization\BeLanguageStringService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;

class TableService extends PsService
{
    protected $internalServerErrorStatusCode, $languageStringService, $languageService, $pagePerPag, $customizeUiService, $coreFieldFilterSettingService, $okStatusCode;

    public function __construct(BeLanguageStringService $languageStringService, LanguageService $languageService, CustomFieldService $customizeUiService, CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        $this->pagePerPag = ps_config::pagPerPage;
        $this->customizeUiService = $customizeUiService;
        $this->languageService = $languageService;
        $this->languageStringService = $languageStringService;
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;

        $this->tableNameCol = Table::name;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->okStatusCode = Constants::okStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
    }

    public function getTables($relation = null, $withNoPag = null, $conds = null, $loading = null)
    {
        $tables = Table::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($conds, function ($query, $conds) {
                // search term
                if (isset($conds['keyword']) && $conds['keyword']) {
                    $query->where($this->tableNameCol, 'LIKE', '%' . $conds['keyword'] . '%');
                }

                if (isset($conds['table_used_type_id']) && $conds['table_used_type_id']) {
                    if ($conds['table_used_type_id'] == 99) {
                        $query->where('table_used_type_id', null);
                    } else {
                        $query->where('table_used_type_id', $conds['table_used_type_id']);
                    }
                }

                // order by
                if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type'])
                    $query->orderBy($conds['order_by'], $conds['order_type']);
                else if (isset($conds['order_by']) && $conds['order_by'])
                    $query->orderBy($conds['order_by']);
            });

        if ($withNoPag) {
            $tables = $tables->get();
        } else {
            $counts = $tables->count();

            if ($loading) {
                $tables = $tables->paginate($counts);
            } else {
                $tables = $tables->paginate(9)->onEachSide(1)->withQueryString();
            }
        }
        return $tables;
    }

    public function getCoreKeyTypes($relation = null, $withNoPag = null, $conds = null, $loading = null)
    {
        $coreKeyTypes = CoreKeyType::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($conds, function ($query, $conds) {
                // search term
                if (isset($conds['keyword']) && $conds['keyword']) {
                    $query->where('name', 'LIKE', '%' . $conds['keyword'] . '%');
                    $query->orWhere('description', 'LIKE', '%' . $conds['keyword'] . '%');
                }

                // order by
                if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type'])
                    $query->orderBy($conds['order_by'], $conds['order_type']);
                else if (isset($conds['order_by']) && $conds['order_by'])
                    $query->orderBy($conds['order_by']);
            });

        if ($withNoPag) {
            $coreKeyTypes = $coreKeyTypes->get();
        } else {
            $counts = $coreKeyTypes->count();

            if ($loading) {
                $coreKeyTypes = $coreKeyTypes->paginate($counts);
            } else {
                $coreKeyTypes = $coreKeyTypes->paginate(9)->onEachSide(1)->withQueryString();
            }
        }
        return $coreKeyTypes;
    }

    public function getTable($id = null)
    {
        $table = Table::when($id, function ($q, $id) {
            $q->where("id", $id);
        })
            ->first();
        return $table;
    }

    public function getCoreKeyType($id = null)
    {
        $coreKeyType = CoreKeyType::when($id, function ($q, $id) {
            $q->where("id", $id);
        })
            ->first();
        return $coreKeyType;
    }

    public function index($request)
    {

        $updaterData = UpdaterData::first();
        if (!empty($updaterData)) {
            $zipFileName = $updaterData->file_name;
        } else {
            $zipFileName = $request->zipFileName;
        }

        $checkPermission = $this->checkPermission($this->viewAnyAbility, Table::class, "admin.index");

        $isloaded = 0;
        $isSorting = 0;

        $tableUsedTypes = TableUsedType::all();

        $search = $request->input('search') ?? '';
        $conds['keyword'] = $search;

        if ($request->loading == 1) {
            $isloaded = 1;
        }

        if ($request->sorting && $request->sorting == 1) {
            $conds['order_by'] = 'name';
            $conds['order_type'] = 'desc';
            $isSorting = 0;
        } else {
            $conds['order_by'] = 'name';
            $conds['order_type'] = 'asc';
            $isSorting = 1;
        }

        $table_used_type_id = 1;
        if ($request->tableUsedTypeId) {
            $table_used_type_id = $request->tableUsedTypeId;
        }
        $conds['table_used_type_id'] = $table_used_type_id;

        if ($request->loading && $request->loading == true) {

            $tables =  TableWithKeyResource::collection($this->getTables(null, null, $conds, 1));
        } else {
            $tables =  TableWithKeyResource::collection($this->getTables(null, null, $conds));
        }

        $dataArr = [
            "checkPermission" => $checkPermission,
            'tables' => $tables,
            'loadMore' => $isloaded,
            'search' => $search,
            'zipFileName' => $zipFileName,
            'tableUsedTypes' => $tableUsedTypes,
            'tableUsedTypeId' => $table_used_type_id,
            'sorting' => $isSorting,
        ];
        return $dataArr;
    }


    public function import($request)
    {

        // clear data From psx_updater_data table
        DB::table('psx_updater_data')->truncate();

        config('app.key', $request->key);

        $selectedFileName = $request->selectedFileName;
        if (!empty($selectedFileName)) {
            $fileName = $selectedFileName;
        } else {

            // extract the zip file
            $zipFile = $request->file("zipFile");
            $zip = new \ZipArchive();
            $zip->open($zipFile);
            $fileName = $zip->getNameIndex(0);

            $zip->extractTo("./");
            $zip->close();
        }

        // read json file that come from zip file
        $file = file_get_contents($fileName);
        $dataFromJsonFileFromZip = json_decode($file);
        $key = $dataFromJsonFileFromZip->key;
        $project = $dataFromJsonFileFromZip->project;
        $customFields = $dataFromJsonFileFromZip->custom_field_infos;
        $coreFields = $dataFromJsonFileFromZip->core_field_infos;
        $tables = $dataFromJsonFileFromZip->tables;
        $coreKeyTypes = $dataFromJsonFileFromZip->core_key_types;
        $languageStrings = $dataFromJsonFileFromZip->language_strings;
        $oldTablesWithPag = $this->getTables();
        $oldTables = $this->getTables(null, 1);
        $oldCoreKeyTypes = $this->getCoreKeyTypes(null, 1);
        $oldCustomFields = $this->customizeUiService->getAll(null, null, 1);
        $oldCoreFields = $this->coreFieldFilterSettingService->getCoreFields(null, null, "label_name as name", 1);


        //        $decryptedKey = explode("\n", Crypt::decryptString($key));
        //        $backendUrl = $decryptedKey[0];
        //        $purchasedCode = $decryptedKey[1];
        //        $psLicenseCode = $decryptedKey[2];
        //
        //        $logMessages = [];

        if ($oldCoreFields->isNotEmpty()) {
            $oldDataOfProject = Project::first();
            //
            //            if ($backendUrl !== $oldDataOfProject->project_url){
            //                $domainObj = new \stdClass();
            //                $domainObj->status = "danger";
            //                $domainObj->message = "Domain Verification is Fail";
            //                $logMessages[] = $domainObj;
            //            } else {
            //                $domainObj = new \stdClass();
            //                $domainObj->status = "success";
            //                $domainObj->message = "Domain Verification is Success";
            //                $logMessages[] = $domainObj;
            //            }
            //
            //            if ($purchasedCode !== $oldDataOfProject->project_code){
            //                $licenseObj = new \stdClass();
            //                $licenseObj->status = "danger";
            //                $licenseObj->message = "License Verification is Fail";
            //                $logMessages[] = $licenseObj;
            //            } else {
            //                $licenseObj = new \stdClass();
            //                $licenseObj->status = "success";
            //                $licenseObj->message = "License Verification is Success";
            //                $logMessages[] = $licenseObj;
            //            }
            //
            //            $dataArr = [
            //                "logMessages" => $logMessages,
            //            ];
            //
            //            return $dataArr;

            $oldDataOfProjectId = $oldDataOfProject->id;
            $oldDataOfPurchaseCode = $oldDataOfProject->project_code;
            $oldDataOfBackendUrl = $oldDataOfProject->project_url;
            $oldDataOfBaseProjectId = $oldDataOfProject->base_project_id;

            $newImportDataOfProjectId = $project->id;
            $newImportDataOfPurchaseCode =  $project->project_code;
            $newImportDataOfBackendUrl =  $project->project_url;
            $newImportDataOfBaseProjectId = $project->base_project_id;

            if ($oldDataOfBaseProjectId !== $newImportDataOfBaseProjectId) {
                $msg = __('core__be_zip_not_import');
                $dataArr = [
                    "baseProjectNotSameMsg" => $msg,
                ];
                return $dataArr;
            }

            if ($oldDataOfPurchaseCode !== $newImportDataOfPurchaseCode || $oldDataOfBackendUrl !== $newImportDataOfBackendUrl) {
                $msg = __('core__be_if_purchase_code_or_url_not_same');
                $dataArr = [
                    "purchaseCodeOrUrlNotSame" => $msg,
                    "selectedZipFileName" => $fileName,
                ];
                return $dataArr;
            } elseif ($oldDataOfProjectId !== $newImportDataOfProjectId) {
                $msg = __('core__be_if_import_proj_not_same');
                $dataArr = [
                    "importedFileName" => $fileName,
                    "baseProjectSameMsg" => $msg,
                ];
                return $dataArr;
            }
        }

        $diffCoreFields = [];
        $diffCustomFields = [];

        if ($oldTables->isEmpty()) {

            DB::beginTransaction();
            try {

                // save or update in projects table
                Project::updateOrCreate(
                    ['base_project_id' =>  $project->base_project_id],
                    [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'project_code' => $project->project_code,
                        'project_url' => $project->project_url,
                        'added_user_id' => Auth::id()
                    ]
                );

                foreach ($customFields as $customField) {
                    // save in customize_ui table
                    $customizeUi = new CustomField();
                    $customizeUi->id  = $customField->id;
                    $customizeUi->table_id  = $customField->table_id;
                    $customizeUi->project_name = $customField->project_name;
                    $customizeUi->project_id = $customField->project_id;
                    $customizeUi->name = $customField->name;
                    $customizeUi->placeholder = $customField->placeholder;
                    $customizeUi->ui_type_id = $customField->ui_type_id;
                    $customizeUi->core_keys_id = $customField->core_keys_id;
                    $customizeUi->is_delete = $customField->is_delete;
                    $customizeUi->data_type = $customField->data_type;
                    $customizeUi->module_name = $customField->module_name;
                    $customizeUi->base_module_name = $customField->base_module_name;
                    $customizeUi->enable = $customField->enable;
                    $customizeUi->mandatory = $customField->mandatory;
                    $customizeUi->is_show_sorting = $customField->is_show_sorting;
                    $customizeUi->ordering = $customField->ordering;
                    $customizeUi->is_show_in_filter = $customField->is_show_in_filter;
                    $customizeUi->is_include_in_hideshow = $customField->is_include_in_hideshow;
                    $customizeUi->is_show = $customField->is_show;
                    $customizeUi->is_core_field = 0;
                    $customizeUi->permission_for_enable_disable = $customField->permission_for_enable_disable;
                    $customizeUi->permission_for_delete = $customField->permission_for_delete;
                    $customizeUi->permission_for_mandatory = $customField->permission_for_mandatory;
                    $customizeUi->added_user_id  = Auth::id();
                    $customizeUi->save();

                    if ($customField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_setting
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $customField->module_name;
                        $screenDisplayUiSetting->key = $customField->core_keys_id;
                        $screenDisplayUiSetting->is_show = $customField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();
                        $screenDisplayUiSetting->save();
                    }
                }

                foreach ($coreFields as $coreField) {
                    // save in core_field_filter_settings table
                    $coreFieldFilterSetting = new CoreField();
                    $coreFieldFilterSetting->id = $coreField->id;
                    $coreFieldFilterSetting->table_id = $coreField->table_id;
                    $coreFieldFilterSetting->project_name = $coreField->project_name;
                    $coreFieldFilterSetting->project_id = $coreField->project_id;
                    $coreFieldFilterSetting->label_name = $coreField->label_name;
                    $coreFieldFilterSetting->module_name = $coreField->module_name;
                    $coreFieldFilterSetting->base_module_name = $coreField->base_module_name;
                    $coreFieldFilterSetting->field_name = $coreField->field_name;
                    $coreFieldFilterSetting->placeholder = $coreField->placeholder;
                    $coreFieldFilterSetting->data_type = $coreField->data_type;
                    $coreFieldFilterSetting->is_delete = $coreField->is_delete;
                    $coreFieldFilterSetting->enable = $coreField->enable;
                    $coreFieldFilterSetting->mandatory = $coreField->mandatory;
                    $coreFieldFilterSetting->is_show_sorting = $coreField->is_show_sorting;
                    $coreFieldFilterSetting->ordering = $coreField->ordering;
                    $coreFieldFilterSetting->is_show_in_filter = $coreField->is_show_in_filter;
                    $coreFieldFilterSetting->is_include_in_hideshow = $coreField->is_include_in_hideshow;
                    $coreFieldFilterSetting->is_show = $coreField->is_show;
                    $coreFieldFilterSetting->is_core_field = 1;
                    $coreFieldFilterSetting->permission_for_enable_disable = $coreField->permission_for_enable_disable;
                    $coreFieldFilterSetting->permission_for_delete = $coreField->permission_for_delete;
                    $coreFieldFilterSetting->permission_for_mandatory = $coreField->permission_for_mandatory;
                    $coreFieldFilterSetting->added_user_id = Auth::id();
                    $coreFieldFilterSetting->save();

                    if ($coreField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_settings
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $coreField->module_name;
                        $screenDisplayUiSetting->key = $coreField->field_name;
                        $screenDisplayUiSetting->is_show = $coreField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();
                        $screenDisplayUiSetting->save();
                    }
                }

                foreach ($tables as $tableObj) {
                    // save in tables table
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

                foreach ($coreKeyTypes as $coreKeyType) {
                    // save in core_key_types table
                    $type = new CoreKeyType();
                    $type->id = $coreKeyType->id;
                    $type->name = $coreKeyType->name;
                    $type->description = $coreKeyType->description;
                    $type->code = $coreKeyType->code;
                    $type->client_code = $coreKeyType->client_code;
                    $type->added_user_id = Auth::id();
                    $type->save();
                }

                // $languages = $this->languageService->getLanguages();
                $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings, false);

                // foreach ($languages as $language){
                //     foreach ($languageStrings as $languageStringObj){
                //         // save in language_strings table
                //         $languageString = new LanguageString();
                //         $languageString->language_id = $language->id;
                //         $languageString->key = $languageStringObj->key;
                //         $languageString->value = $languageStringObj->value;
                //         $languageString->added_user_id = Auth::id();
                //         $languageString->save();

                //     }
                //     // update lang json files
                //     $fileName = $language->symbol . '.json';
                //     $lang_str = $this->languageStringService->getLanguageStrings($language->id);
                //     generateLangStrJson($fileName, $lang_str);
                // }
                DB::commit();
                $msg = "First Import success";

                $dataInputObj = new \stdClass();
                $dataInputObj->status = "success";
                $dataInputObj->message = "Data Input is Success";
                $logMessages[] = $dataInputObj;

                $dataArr = [
                    "logMessages" => $logMessages,
                    "importSuccessMsg" => $msg,
                    'needRefresh' => $needRefresh,
                    "tables" => $oldTablesWithPag,
                    "recentImportedFileName" => $fileName
                ];

                // clear data From psx_updater_data table
                DB::table('psx_updater_data')->truncate();

                return $dataArr;
            } catch (\Throwable $e) {
                DB::rollBack();
                $dataInputObj = new \stdClass();
                $dataInputObj->status = "danger";
                $dataInputObj->message = "Data Input is Fail";
                $logMessages[] = $dataInputObj;
                $dataArr = [
                    "logMessages" => $logMessages,
                ];
                // echo json_encode($e);exit;
                return $dataArr;
            }
        } else {
            $tableIds = collect($tables)->pluck("id");
            $oldTableIds = $oldTables->pluck("id");

            $coreKeyTypeIds = collect($coreKeyTypes)->pluck("id");
            $oldCoreKeyTypeIds = $oldCoreKeyTypes->pluck("id");

            $coreFieldIds = collect($coreFields)->pluck("id");
            $oldCoreFieldIds = $oldCoreFields->pluck("id");

            $newTableIds = $tableIds->diff($oldTableIds);
            $newCoreKeyTypeIds = $coreKeyTypeIds->diff($oldCoreKeyTypeIds);
            $newCoreFieldIds = $coreFieldIds->diff($oldCoreFieldIds);

            $diffIsDeleteStatusCoreField = [];
            $diffIsDeleteStatusCustomField = [];

            foreach ($oldCoreFields as $oldCoreField) {
                foreach ($coreFields as $coreField) {
                    if ($coreField->id == $oldCoreField->id) {
                        if ($oldCoreField->is_delete !== $coreField->is_delete) {
                            array_push($diffIsDeleteStatusCoreField, $oldCoreField->id);
                        }
                    }
                }
            }

            foreach ($oldCustomFields as $oldCustomField) {
                foreach ($customFields as $customField) {
                    if ($customField->id == $oldCustomField->id) {
                        if ($oldCustomField->is_delete !== $customField->is_delete) {
                            array_push($diffIsDeleteStatusCustomField, $oldCustomField->id);
                        }
                    }
                }
            }

            $deleteStatusDiffIds = array_merge($diffIsDeleteStatusCoreField, $diffIsDeleteStatusCustomField);


            $customFieldsFromPsAdmin = $this->customizeUiService->getAll(null, null, 1, null, null, null, null, null, null, null, $deleteStatusDiffIds);
            $coreFieldsFromPsAdmin = $this->coreFieldFilterSettingService->getCoreFields(null, null, null, 1, null, $deleteStatusDiffIds);

            if ($coreFieldsFromPsAdmin->isNotEmpty()) {
                foreach ($coreFieldsFromPsAdmin as $coreFieldFromPsAdmin) {
                    foreach ($coreFields as $coreFieldFromBuilder) {
                        if ($coreFieldFromBuilder->id == $coreFieldFromPsAdmin->id) {
                            $coreFieldFromPsAdmin->is_delete = $coreFieldFromBuilder->is_delete;
                            $coreFieldFromPsAdmin->update();
                        }
                    }
                }
            }

            if ($customFieldsFromPsAdmin->isNotEmpty()) {
                foreach ($customFieldsFromPsAdmin as $customFieldFromPsAdmin) {
                    foreach ($customFields as $customFieldFromBuilder) {
                        if ($customFieldFromBuilder->id == $customFieldFromPsAdmin->id) {
                            $customFieldFromPsAdmin->is_delete = $customFieldFromBuilder->is_delete;
                            $customFieldFromPsAdmin->update();
                        }
                    }
                }
            }

            // if there have new table, will save
            if ($newTableIds->isNotEmpty()) {
                $newTables = collect($tables)->whereIn("id", $newTableIds);
                foreach ($newTables as $tableObj) {
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

            // if there have new core_key_type, will save
            if ($newCoreKeyTypeIds->isNotEmpty()) {
                $newCoreKeyTypes = collect($coreKeyTypes)->whereIn("id", $newCoreKeyTypeIds);
                foreach ($newCoreKeyTypes as $coreKeyTypeObj) {
                    $coreKeyType = new CoreKeyType();
                    $coreKeyType->id = $coreKeyTypeObj->id;
                    $coreKeyType->name = $coreKeyTypeObj->name;
                    $coreKeyType->description = $coreKeyTypeObj->description;
                    $coreKeyType->code = $coreKeyTypeObj->code;
                    $coreKeyType->client_code = $coreKeyTypeObj->client_code;
                    $coreKeyType->added_user_id = Auth::id();
                    $coreKeyType->save();
                }
            }

            // if there have diff table, will update
            $diffTableIdsArr = [];

            foreach ($oldTables as $oldTable) {
                foreach ($tables as $table) {
                    if ($oldTable->id == $table->id) {
                        if ($oldTable->is_only_for_core_field !== $table->is_only_for_core_field) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->description !== $table->description) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->core_key_type_id !== $table->core_key_type_id) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->table_used_type_id !== $table->table_used_type_id) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        }
                    }
                }
            }

            if (collect($diffTableIdsArr)->isNotEmpty()) {
                $newTables = collect($tables)->whereIn("id", $diffTableIdsArr);
                foreach ($newTables as $tableObj) {
                    $table = $this->getTable($tableObj->id);
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


            // if there have new core field, will save
            if ($newCoreFieldIds->isNotEmpty()) {
                $newCoreFields = collect($coreFields)->whereIn("id", $newCoreFieldIds);
                // echo json_encode($newCoreFields);exit;
                foreach ($newCoreFields as $newCoreField) {
                    // save in core_field_filter_settings table
                    $coreFieldFilterSetting = new CoreField();
                    $coreFieldFilterSetting->id = $newCoreField->id;
                    $coreFieldFilterSetting->table_id = $newCoreField->table_id;
                    $coreFieldFilterSetting->project_name = $newCoreField->project_name;
                    $coreFieldFilterSetting->project_id = $newCoreField->project_id;
                    $coreFieldFilterSetting->label_name = $newCoreField->label_name;
                    $coreFieldFilterSetting->module_name = $newCoreField->module_name;
                    $coreFieldFilterSetting->base_module_name = $newCoreField->base_module_name;
                    $coreFieldFilterSetting->field_name = $newCoreField->field_name;
                    $coreFieldFilterSetting->placeholder = $newCoreField->placeholder;
                    $coreFieldFilterSetting->data_type = $newCoreField->data_type;
                    $coreFieldFilterSetting->is_delete = $newCoreField->is_delete;
                    $coreFieldFilterSetting->enable = $newCoreField->enable;
                    $coreFieldFilterSetting->mandatory = $newCoreField->mandatory;
                    $coreFieldFilterSetting->is_show_sorting = $newCoreField->is_show_sorting;
                    $coreFieldFilterSetting->ordering = $newCoreField->ordering;
                    $coreFieldFilterSetting->is_show_in_filter = $newCoreField->is_show_in_filter;
                    $coreFieldFilterSetting->is_include_in_hideshow = $newCoreField->is_include_in_hideshow;
                    $coreFieldFilterSetting->is_show = $newCoreField->is_show;
                    $coreFieldFilterSetting->is_core_field = 1;
                    $coreFieldFilterSetting->permission_for_enable_disable = $newCoreField->permission_for_enable_disable;
                    $coreFieldFilterSetting->permission_for_delete = $newCoreField->permission_for_delete;
                    $coreFieldFilterSetting->permission_for_mandatory = $newCoreField->permission_for_mandatory;
                    $coreFieldFilterSetting->added_user_id = Auth::id();
                    $coreFieldFilterSetting->save();

                    if ($newCoreField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_settings
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $newCoreField->module_name;
                        $screenDisplayUiSetting->key = $newCoreField->field_name;
                        $screenDisplayUiSetting->is_show = $newCoreField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();

                        $screenDisplayUiSetting->save();
                    }

                    // $languages = $this->languageService->getLanguages();
                    // foreach ($languages as $language){
                    //     foreach ($languageStrings as $languageStringObj) {
                    //         if ($languageStringObj->key == $newCoreField->label_name){
                    //             // save in language_strings table
                    //             $languageString = new LanguageString();
                    //             $languageString->language_id = $language->id;
                    //             $languageString->key = $languageStringObj->key;
                    //             $languageString->value = $languageStringObj->value;
                    //             $languageString->added_user_id = Auth::id();
                    //             $languageString->save();
                    //         }

                    //         if ($languageStringObj->key == $newCoreField->placeholder){
                    //             // save in language_strings table
                    //             $languageString = new LanguageString();
                    //             $languageString->language_id = $language->id;
                    //             $languageString->key = $languageStringObj->key;
                    //             $languageString->value = $languageStringObj->value;
                    //             $languageString->added_user_id = Auth::id();
                    //             $languageString->save();
                    //         }
                    //     }
                    // }

                }
            }

            $customFieldIds = collect($customFields)->pluck("id");
            $oldCustomFieldIds = $oldCustomFields->pluck("id");

            $newCustomFieldIds = $customFieldIds->diff($oldCustomFieldIds);
            // if there have new custom field, will save
            DB::beginTransaction();
            try {
                if ($newCustomFieldIds->isNotEmpty()) {
                    $newCustomFields = collect($customFields)->whereIn("id", $newCustomFieldIds);
                    foreach ($newCustomFields as $newCustomField) {
                        //                        return $newCustomField;
                        // save in core_field_filter_settings table
                        $customizeUi = new CustomField();
                        $customizeUi->id  = $newCustomField->id;
                        $customizeUi->table_id  = $newCustomField->table_id;
                        $customizeUi->project_name = $newCustomField->project_name;
                        $customizeUi->project_id = $newCustomField->project_id;
                        $customizeUi->name = $newCustomField->name;
                        $customizeUi->placeholder = $newCustomField->placeholder;
                        $customizeUi->ui_type_id = $newCustomField->ui_type_id;
                        $customizeUi->core_keys_id = $newCustomField->core_keys_id;
                        $customizeUi->is_delete = $newCustomField->is_delete;
                        $customizeUi->data_type = $newCustomField->data_type;
                        $customizeUi->module_name = $newCustomField->module_name;
                        $customizeUi->base_module_name = $newCustomField->base_module_name;
                        $customizeUi->enable = $newCustomField->enable;
                        $customizeUi->mandatory = $newCustomField->mandatory;
                        $customizeUi->is_show_sorting = $newCustomField->is_show_sorting;
                        $customizeUi->ordering = $newCustomField->ordering;
                        $customizeUi->is_show_in_filter = $newCustomField->is_show_in_filter;
                        $customizeUi->is_include_in_hideshow = $newCustomField->is_include_in_hideshow;
                        $customizeUi->is_show = $newCustomField->is_show;
                        $customizeUi->is_core_field = 0;
                        $customizeUi->permission_for_enable_disable = $newCustomField->permission_for_enable_disable;
                        $customizeUi->permission_for_delete = $newCustomField->permission_for_delete;
                        $customizeUi->permission_for_mandatory = $newCustomField->permission_for_mandatory;
                        $customizeUi->added_user_id  = Auth::id();
                        $customizeUi->save();

                        if ($newCustomField->is_include_in_hideshow == 1) {
                            // save in screen_display_ui_setting
                            $screenDisplayUiSetting = new DynamicColumnVisibility();
                            $screenDisplayUiSetting->module_name = $newCustomField->module_name;
                            $screenDisplayUiSetting->key = $newCustomField->core_keys_id;
                            $screenDisplayUiSetting->is_show = $newCustomField->is_show;
                            $screenDisplayUiSetting->added_user_id = Auth::id();
                            $screenDisplayUiSetting->save();
                        }

                        // $languages = $this->languageService->getLanguages();
                        // foreach ($languages as $language){
                        //     foreach ($languageStrings as $languageStringObj) {
                        //         if ($languageStringObj->key == $newCustomField->name){
                        //             // save in language_strings table
                        //             $languageString = new LanguageString();
                        //             $languageString->language_id = $language->id;
                        //             $languageString->key = $languageStringObj->key;
                        //             $languageString->value = $languageStringObj->value;
                        //             $languageString->added_user_id = Auth::id();
                        //             $languageString->save();
                        //         }

                        //         if ($languageStringObj->key == $newCustomField->placeholder){
                        //             // save in language_strings table
                        //             $languageString = new LanguageString();
                        //             $languageString->language_id = $language->id;
                        //             $languageString->key = $languageStringObj->key;
                        //             $languageString->value = $languageStringObj->value;
                        //             $languageString->added_user_id = Auth::id();
                        //             $languageString->save();
                        //         }
                        //     }
                        // }

                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                //                return $e->getMessage();
            }

            foreach ($oldCoreFields as $oldCoreField) {
                foreach ($coreFields as $coreField) {
                    if ($coreField->id == $oldCoreField->id) {
                        if ($oldCoreField->mandatory !== $coreField->mandatory || $oldCoreField->is_include_in_hideshow !== $coreField->is_include_in_hideshow || $oldCoreField->is_show !== $coreField->is_show || $oldCoreField->enable !== $coreField->enable || $oldCoreField->ordering !== $coreField->ordering || $oldCoreField->is_show_in_filter !== $coreField->is_show_in_filter || $oldCoreField->is_show_sorting !== $coreField->is_show_sorting) {
                            array_push($diffCoreFields, $oldCoreField->id);
                        }
                    }
                }
            }

            foreach ($oldCustomFields as $oldCustomField) {
                foreach ($customFields as $customField) {
                    if ($customField->id == $oldCustomField->id) {
                        if ($oldCustomField->mandatory !== $customField->mandatory || $oldCustomField->is_include_in_hideshow !== $customField->is_include_in_hideshow || $oldCustomField->is_show !== $customField->is_show || $oldCustomField->enable !== $customField->enable || $oldCustomField->ordering !== $customField->ordering || $oldCustomField->is_show_in_filter !== $customField->is_show_in_filter || $oldCustomField->is_show_sorting !== $customField->is_show_sorting) {
                            array_push($diffCustomFields, $oldCustomField->id);
                        }
                    }
                }
            }

            // if (collect($diffCustomFields)->isEmpty() && collect($diffCoreFields)->isEmpty()) {
            //     $languages = $this->languageService->getLanguages();
            //     foreach ($languages as $language) {
            //         // update lang json files
            //         $fileName = $language->symbol . '.json';
            //         $lang_str = $this->languageStringService->getLanguageStrings($language->id);
            //         generateLangStrJson($fileName, $lang_str);
            //     }
            // }

        }
        $mergedDiffIds = array_merge($diffCoreFields, $diffCustomFields);

        $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings);

        if (collect($mergedDiffIds)->isEmpty()) {

            // $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings);

            $msg = "Import success";
            $dataArr = [
                "importSuccessMsg" => $msg,
                'needRefresh' => $needRefresh,
                "tables" => $oldTablesWithPag,
                "recentImportedFileName" => $fileName
            ];
            return $dataArr;
        }
        $dataArr = [
            "tables" => $oldTablesWithPag,
            "diffIds" => $mergedDiffIds,
            "recentImportedFileName" => $fileName
        ];

        // clear data From psx_updater_data table
        DB::table('psx_updater_data')->truncate();

        return $dataArr;
    }

    public function overrideFieldsAndLangs($request)
    {
        $fileName = $request->recentImportedFileName;
        $diffIds = $request->diffIds;
        //        return $diffIds;
        $file = file_get_contents($fileName);
        $dataFromJsonFileFromZip = json_decode($file);
        $customFieldsFromBuilder = $dataFromJsonFileFromZip->custom_field_infos;
        $customFields = $this->customizeUiService->getAll(null, null, 1, null, null, null, null, null, null, null, $diffIds);
        $coreFieldsFromBuilder = $dataFromJsonFileFromZip->core_field_infos;
        $coreFields = $this->coreFieldFilterSettingService->getCoreFields(null, null, null, 1, null, $diffIds);
        $languageStrings = $dataFromJsonFileFromZip->language_strings;

        if ($coreFields->isNotEmpty()) {
            foreach ($coreFields as $coreField) {
                foreach ($coreFieldsFromBuilder as $coreFieldFromBuilder) {
                    if ($coreFieldFromBuilder->id == $coreField->id) {
                        $coreField->mandatory = $coreFieldFromBuilder->mandatory;
                        $coreField->enable = $coreFieldFromBuilder->enable;
                        $coreField->ordering = $coreFieldFromBuilder->ordering;
                        $coreField->is_show_sorting = $coreFieldFromBuilder->is_show_sorting;
                        $coreField->is_show_in_filter = $coreFieldFromBuilder->is_show_in_filter;
                        $coreField->is_include_in_hideshow = $coreFieldFromBuilder->is_include_in_hideshow;
                        $coreField->is_show = $coreFieldFromBuilder->is_show;
                        $coreField->update();
                    }
                }
            }
        }

        if ($customFields->isNotEmpty()) {
            foreach ($customFields as $customField) {
                foreach ($customFieldsFromBuilder as $customFieldFromBuilder) {
                    if ($customFieldFromBuilder->id == $customField->id) {
                        $customField->mandatory = $customFieldFromBuilder->mandatory;
                        $customField->enable = $customFieldFromBuilder->enable;
                        $customField->ordering = $customFieldFromBuilder->ordering;
                        $customField->is_show_sorting = $customFieldFromBuilder->is_show_sorting;
                        $customField->is_show_in_filter = $customFieldFromBuilder->is_show_in_filter;
                        $customField->is_include_in_hideshow = $customFieldFromBuilder->is_include_in_hideshow;
                        $customField->is_show = $customFieldFromBuilder->is_show;
                        $customField->update();
                    }
                }
            }
        }

        // update lang json files
        // $languages = $this->languageService->getLanguages();
        // foreach ($languages as $language){
        //     $fileName = $language->symbol . '.json';
        //     $lang_str = $this->languageStringService->getLanguageStrings($language->id);
        //     generateLangStrJson($fileName, $lang_str);
        // }
        $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings, false);

        $msg = "Import success";
        $dataArr = [
            "importSuccessMsg" => $msg,
            'needRefresh' => $needRefresh,
            "recentImportedFileName" => $fileName
        ];

        // clear data From psx_updater_data table
        DB::table('psx_updater_data')->truncate();

        return $dataArr;
        // return 'overwrite success';

    }

    public function handleProjectNotSame($request)
    {
        $fileName = $request->importedFileName;
        // Project::truncate();
        Table::truncate();
        CustomField::truncate();
        CoreField::truncate();
        DynamicColumnVisibility::truncate();


        // read json file that come from zip file
        $file = file_get_contents($fileName);
        $dataFromJsonFileFromZip = json_decode($file);
        $project = $dataFromJsonFileFromZip->project;
        $customFields = $dataFromJsonFileFromZip->custom_field_infos;
        $coreFields = $dataFromJsonFileFromZip->core_field_infos;
        $tables = $dataFromJsonFileFromZip->tables;
        $coreKeyTypes = $dataFromJsonFileFromZip->core_key_types;
        $languageStrings = $dataFromJsonFileFromZip->language_strings;

        DB::beginTransaction();
        try {

            // save or update in projects table
            Project::updateOrCreate(
                ['base_project_id' =>  $project->base_project_id],
                [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'project_code' => $project->project_code,
                    'project_url' => $project->project_url,
                    'added_user_id' => Auth::id()
                ]
            );

            foreach ($customFields as $customField) {
                // save in customize_ui table
                $customizeUi = new CustomField();
                $customizeUi->id  = $customField->id;
                $customizeUi->table_id  = $customField->table_id;
                $customizeUi->project_name = $customField->project_name;
                $customizeUi->project_id = $customField->project_id;
                $customizeUi->name = $customField->name;
                $customizeUi->placeholder = $customField->placeholder;
                $customizeUi->ui_type_id = $customField->ui_type_id;
                $customizeUi->core_keys_id = $customField->core_keys_id;
                $customizeUi->is_delete = $customField->is_delete;
                $customizeUi->data_type = $customField->data_type;
                $customizeUi->module_name = $customField->module_name;
                $customizeUi->base_module_name = $customField->base_module_name;
                $customizeUi->enable = $customField->enable;
                $customizeUi->mandatory = $customField->mandatory;
                $customizeUi->is_show_sorting = $customField->is_show_sorting;
                $customizeUi->ordering = $customField->ordering;
                $customizeUi->is_show_in_filter = $customField->is_show_in_filter;
                $customizeUi->is_include_in_hideshow = $customField->is_include_in_hideshow;
                $customizeUi->is_show = $customField->is_show;
                $customizeUi->is_core_field = 0;
                $customizeUi->permission_for_enable_disable = $customField->permission_for_enable_disable;
                $customizeUi->permission_for_delete = $customField->permission_for_delete;
                $customizeUi->permission_for_mandatory = $customField->permission_for_mandatory;
                $customizeUi->added_user_id  = Auth::id();
                $customizeUi->save();

                if ($customField->is_include_in_hideshow == 1) {
                    // save in screen_display_ui_setting
                    $screenDisplayUiSetting = new DynamicColumnVisibility();
                    $screenDisplayUiSetting->module_name = $customField->module_name;
                    $screenDisplayUiSetting->key = $customField->core_keys_id;
                    $screenDisplayUiSetting->is_show = $customField->is_show;
                    $screenDisplayUiSetting->added_user_id = Auth::id();
                    $screenDisplayUiSetting->save();
                }
            }

            foreach ($coreFields as $coreField) {
                // save in core_field_filter_settings table
                $coreFieldFilterSetting = new CoreField();
                $coreFieldFilterSetting->id = $coreField->id;
                $coreFieldFilterSetting->table_id = $coreField->table_id;
                $coreFieldFilterSetting->project_name = $coreField->project_name;
                $coreFieldFilterSetting->project_id = $coreField->project_id;
                $coreFieldFilterSetting->label_name = $coreField->label_name;
                $coreFieldFilterSetting->module_name = $coreField->module_name;
                $coreFieldFilterSetting->base_module_name = $coreField->base_module_name;
                $coreFieldFilterSetting->field_name = $coreField->field_name;
                $coreFieldFilterSetting->placeholder = $coreField->placeholder;
                $coreFieldFilterSetting->data_type = $coreField->data_type;
                $coreFieldFilterSetting->is_delete = $coreField->is_delete;
                $coreFieldFilterSetting->enable = $coreField->enable;
                $coreFieldFilterSetting->mandatory = $coreField->mandatory;
                $coreFieldFilterSetting->is_show_sorting = $coreField->is_show_sorting;
                $coreFieldFilterSetting->ordering = $coreField->ordering;
                $coreFieldFilterSetting->is_show_in_filter = $coreField->is_show_in_filter;
                $coreFieldFilterSetting->is_include_in_hideshow = $coreField->is_include_in_hideshow;
                $coreFieldFilterSetting->is_show = $coreField->is_show;
                $coreFieldFilterSetting->is_core_field = 1;
                $coreFieldFilterSetting->permission_for_enable_disable = $coreField->permission_for_enable_disable;
                $coreFieldFilterSetting->permission_for_delete = $coreField->permission_for_delete;
                $coreFieldFilterSetting->permission_for_mandatory = $coreField->permission_for_mandatory;
                $coreFieldFilterSetting->added_user_id = Auth::id();
                $coreFieldFilterSetting->save();

                if ($coreField->is_include_in_hideshow == 1) {
                    // save in screen_display_ui_settings
                    $screenDisplayUiSetting = new DynamicColumnVisibility();
                    $screenDisplayUiSetting->module_name = $coreField->module_name;
                    $screenDisplayUiSetting->key = $coreField->field_name;
                    $screenDisplayUiSetting->is_show = $coreField->is_show;
                    $screenDisplayUiSetting->added_user_id = Auth::id();
                    $screenDisplayUiSetting->save();
                }
            }

            foreach ($tables as $tableObj) {
                // save in tables table
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

            $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings, false);

            // $languages = $this->languageService->getLanguages();
            // foreach ($languages as $language){
            //     foreach ($languageStrings as $languageStringObj){
            //         // save in language_strings table
            //         $languageString = new LanguageString();
            //         $languageString->language_id = $language->id;
            //         $languageString->key = $languageStringObj->key;
            //         $languageString->value = $languageStringObj->value;
            //         $languageString->added_user_id = Auth::id();
            //         $languageString->save();

            //     }
            //     // update lang json files
            //     $fileName = $language->symbol . '.json';
            //     $lang_str = $this->languageStringService->getLanguageStrings($language->id);
            //     generateLangStrJson($fileName, $lang_str);
            // }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $e->getMessage() . "<br>" . $e->getLine();
        }

        $activatedFileName = ActivatedFileName::first();
        $activatedFileName->is_imported = 1;
        $activatedFileName->update();

        $msg = "Import success";
        $dataArr = [
            "importSuccessMsg" => $msg,
            'needRefresh' => $needRefresh,
            "recentImportedFileName" => $fileName
        ];
        return $dataArr;
        // return 'replace success';
    }

    // for api start
    public function takeBuilderApiKey($request)
    {
        $apiKey = $request->api_key;
        $project = Project::first();
        if (!empty($project)) {
            $project->api_key = $apiKey;
            $project->update();

            return ['success' =>  "api key have been saved successfully", 'status' =>  $this->okStatusCode];
        }

        return ['error' =>  "api key can not save", 'status' =>  $this->internalServerErrorStatusCode];
    }

    public function autoSync($request)
    {

        // clear data From psx_updater_data table
        DB::table('psx_updater_data')->truncate();

        config('app.key', $request->key);


        // read json file that come from zip file
        $dataFromJsonFileFromZip = json_decode($request->json());
        $key = $dataFromJsonFileFromZip->key;
        $project = $dataFromJsonFileFromZip->project;
        $customFields = $dataFromJsonFileFromZip->custom_field_infos;
        $coreFields = $dataFromJsonFileFromZip->core_field_infos;
        $tables = $dataFromJsonFileFromZip->tables;
        $coreKeyTypes = $dataFromJsonFileFromZip->core_key_types;
        $languageStrings = $dataFromJsonFileFromZip->language_strings;
        $oldTablesWithPag = $this->getTables();
        $oldTables = $this->getTables(null, 1);
        $oldCoreKeyTypes = $this->getCoreKeyTypes(null, 1);
        $oldCustomFields = $this->customizeUiService->getAll(null, null, 1);
        $oldCoreFields = $this->coreFieldFilterSettingService->getCoreFields(null, null, "label_name as name", 1);


        //        $decryptedKey = explode("\n", Crypt::decryptString($key));
        //        $backendUrl = $decryptedKey[0];
        //        $purchasedCode = $decryptedKey[1];
        //        $psLicenseCode = $decryptedKey[2];
        //
        //        $logMessages = [];

        if ($oldCoreFields->isNotEmpty()) {
            $oldDataOfProject = Project::first();
            //
            //            if ($backendUrl !== $oldDataOfProject->project_url){
            //                $domainObj = new \stdClass();
            //                $domainObj->status = "danger";
            //                $domainObj->message = "Domain Verification is Fail";
            //                $logMessages[] = $domainObj;
            //            } else {
            //                $domainObj = new \stdClass();
            //                $domainObj->status = "success";
            //                $domainObj->message = "Domain Verification is Success";
            //                $logMessages[] = $domainObj;
            //            }
            //
            //            if ($purchasedCode !== $oldDataOfProject->project_code){
            //                $licenseObj = new \stdClass();
            //                $licenseObj->status = "danger";
            //                $licenseObj->message = "License Verification is Fail";
            //                $logMessages[] = $licenseObj;
            //            } else {
            //                $licenseObj = new \stdClass();
            //                $licenseObj->status = "success";
            //                $licenseObj->message = "License Verification is Success";
            //                $logMessages[] = $licenseObj;
            //            }
            //
            //            $dataArr = [
            //                "logMessages" => $logMessages,
            //            ];
            //
            //            return $dataArr;

            $oldDataOfProjectId = $oldDataOfProject->id;
            $oldDataOfPurchaseCode = $oldDataOfProject->project_code;
            $oldDataOfBackendUrl = $oldDataOfProject->project_url;
            $oldDataOfBaseProjectId = $oldDataOfProject->base_project_id;

            $newImportDataOfProjectId = $project->id;
            $newImportDataOfPurchaseCode =  $project->project_code;
            $newImportDataOfBackendUrl =  $project->project_url;
            $newImportDataOfBaseProjectId = $project->base_project_id;

            if ($oldDataOfBaseProjectId !== $newImportDataOfBaseProjectId) {
                $msg = __('core__be_zip_not_import');
                $dataArr = [
                    "baseProjectNotSameMsg" => $msg,
                ];
                return $dataArr;
            }

            if ($oldDataOfPurchaseCode !== $newImportDataOfPurchaseCode || $oldDataOfBackendUrl !== $newImportDataOfBackendUrl) {
                $msg = __('core__be_if_purchase_code_or_url_not_same');
                $dataArr = [
                    "purchaseCodeOrUrlNotSame" => $msg,
                    // "selectedZipFileName" => $fileName,
                ];
                return $dataArr;
            } elseif ($oldDataOfProjectId !== $newImportDataOfProjectId) {
                $msg = __('core__be_if_import_proj_not_same');
                $dataArr = [
                    // "importedFileName" => $fileName,
                    "baseProjectSameMsg" => $msg,
                ];
                return $dataArr;
            }
        }

        $diffCoreFields = [];
        $diffCustomFields = [];

        if ($oldTables->isEmpty()) {

            DB::beginTransaction();
            try {

                // save or update in projects table
                Project::updateOrCreate(
                    ['base_project_id' =>  $project->base_project_id],
                    [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'project_code' => $project->project_code,
                        'project_url' => $project->project_url,
                        'added_user_id' => Auth::id()
                    ]
                );

                foreach ($customFields as $customField) {
                    // save in customize_ui table
                    $customizeUi = new CustomField();
                    $customizeUi->id  = $customField->id;
                    $customizeUi->table_id  = $customField->table_id;
                    $customizeUi->project_name = $customField->project_name;
                    $customizeUi->project_id = $customField->project_id;
                    $customizeUi->name = $customField->name;
                    $customizeUi->placeholder = $customField->placeholder;
                    $customizeUi->ui_type_id = $customField->ui_type_id;
                    $customizeUi->core_keys_id = $customField->core_keys_id;
                    $customizeUi->is_delete = $customField->is_delete;
                    $customizeUi->data_type = $customField->data_type;
                    $customizeUi->module_name = $customField->module_name;
                    $customizeUi->base_module_name = $customField->base_module_name;
                    $customizeUi->enable = $customField->enable;
                    $customizeUi->mandatory = $customField->mandatory;
                    $customizeUi->is_show_sorting = $customField->is_show_sorting;
                    $customizeUi->ordering = $customField->ordering;
                    $customizeUi->is_show_in_filter = $customField->is_show_in_filter;
                    $customizeUi->is_include_in_hideshow = $customField->is_include_in_hideshow;
                    $customizeUi->is_show = $customField->is_show;
                    $customizeUi->is_core_field = 0;
                    $customizeUi->permission_for_enable_disable = $customField->permission_for_enable_disable;
                    $customizeUi->permission_for_delete = $customField->permission_for_delete;
                    $customizeUi->permission_for_mandatory = $customField->permission_for_mandatory;
                    $customizeUi->added_user_id  = Auth::id();
                    $customizeUi->save();

                    if ($customField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_setting
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $customField->module_name;
                        $screenDisplayUiSetting->key = $customField->core_keys_id;
                        $screenDisplayUiSetting->is_show = $customField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();
                        $screenDisplayUiSetting->save();
                    }
                }

                foreach ($coreFields as $coreField) {
                    // save in core_field_filter_settings table
                    $coreFieldFilterSetting = new CoreField();
                    $coreFieldFilterSetting->id = $coreField->id;
                    $coreFieldFilterSetting->table_id = $coreField->table_id;
                    $coreFieldFilterSetting->project_name = $coreField->project_name;
                    $coreFieldFilterSetting->project_id = $coreField->project_id;
                    $coreFieldFilterSetting->label_name = $coreField->label_name;
                    $coreFieldFilterSetting->module_name = $coreField->module_name;
                    $coreFieldFilterSetting->base_module_name = $coreField->base_module_name;
                    $coreFieldFilterSetting->field_name = $coreField->field_name;
                    $coreFieldFilterSetting->placeholder = $coreField->placeholder;
                    $coreFieldFilterSetting->data_type = $coreField->data_type;
                    $coreFieldFilterSetting->is_delete = $coreField->is_delete;
                    $coreFieldFilterSetting->enable = $coreField->enable;
                    $coreFieldFilterSetting->mandatory = $coreField->mandatory;
                    $coreFieldFilterSetting->is_show_sorting = $coreField->is_show_sorting;
                    $coreFieldFilterSetting->ordering = $coreField->ordering;
                    $coreFieldFilterSetting->is_show_in_filter = $coreField->is_show_in_filter;
                    $coreFieldFilterSetting->is_include_in_hideshow = $coreField->is_include_in_hideshow;
                    $coreFieldFilterSetting->is_show = $coreField->is_show;
                    $coreFieldFilterSetting->is_core_field = 1;
                    $coreFieldFilterSetting->permission_for_enable_disable = $coreField->permission_for_enable_disable;
                    $coreFieldFilterSetting->permission_for_delete = $coreField->permission_for_delete;
                    $coreFieldFilterSetting->permission_for_mandatory = $coreField->permission_for_mandatory;
                    $coreFieldFilterSetting->added_user_id = Auth::id();
                    $coreFieldFilterSetting->save();

                    if ($coreField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_settings
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $coreField->module_name;
                        $screenDisplayUiSetting->key = $coreField->field_name;
                        $screenDisplayUiSetting->is_show = $coreField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();
                        $screenDisplayUiSetting->save();
                    }
                }

                foreach ($tables as $tableObj) {
                    // save in tables table
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

                foreach ($coreKeyTypes as $coreKeyType) {
                    // save in core_key_types table
                    $type = new CoreKeyType();
                    $type->id = $coreKeyType->id;
                    $type->name = $coreKeyType->name;
                    $type->description = $coreKeyType->description;
                    $type->code = $coreKeyType->code;
                    $type->client_code = $coreKeyType->client_code;
                    $type->added_user_id = Auth::id();
                    $type->save();
                }

                // $languages = $this->languageService->getLanguages();
                $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings, false);

                // foreach ($languages as $language){
                //     foreach ($languageStrings as $languageStringObj){
                //         // save in language_strings table
                //         $languageString = new LanguageString();
                //         $languageString->language_id = $language->id;
                //         $languageString->key = $languageStringObj->key;
                //         $languageString->value = $languageStringObj->value;
                //         $languageString->added_user_id = Auth::id();
                //         $languageString->save();

                //     }
                //     // update lang json files
                //     $fileName = $language->symbol . '.json';
                //     $lang_str = $this->languageStringService->getLanguageStrings($language->id);
                //     generateLangStrJson($fileName, $lang_str);
                // }
                DB::commit();
                $msg = "First Import success";

                $dataInputObj = new \stdClass();
                $dataInputObj->status = "success";
                $dataInputObj->message = "Data Input is Success";
                $logMessages[] = $dataInputObj;

                $dataArr = [
                    "logMessages" => $logMessages,
                    "importSuccessMsg" => $msg,
                    'needRefresh' => $needRefresh,
                    "tables" => $oldTablesWithPag,
                    "recentImportedFileName" => $fileName
                ];

                // clear data From psx_updater_data table
                DB::table('psx_updater_data')->truncate();

                return $dataArr;
            } catch (\Throwable $e) {
                DB::rollBack();
                $dataInputObj = new \stdClass();
                $dataInputObj->status = "danger";
                $dataInputObj->message = "Data Input is Fail";
                $logMessages[] = $dataInputObj;
                $dataArr = [
                    "logMessages" => $logMessages,
                ];
                // echo json_encode($e);exit;
                return $dataArr;
            }
        } else {
            $tableIds = collect($tables)->pluck("id");
            $oldTableIds = $oldTables->pluck("id");

            $coreKeyTypeIds = collect($coreKeyTypes)->pluck("id");
            $oldCoreKeyTypeIds = $oldCoreKeyTypes->pluck("id");

            $coreFieldIds = collect($coreFields)->pluck("id");
            $oldCoreFieldIds = $oldCoreFields->pluck("id");

            $newTableIds = $tableIds->diff($oldTableIds);
            $newCoreKeyTypeIds = $coreKeyTypeIds->diff($oldCoreKeyTypeIds);
            $newCoreFieldIds = $coreFieldIds->diff($oldCoreFieldIds);

            $diffIsDeleteStatusCoreField = [];
            $diffIsDeleteStatusCustomField = [];

            foreach ($oldCoreFields as $oldCoreField) {
                foreach ($coreFields as $coreField) {
                    if ($coreField->id == $oldCoreField->id) {
                        if ($oldCoreField->is_delete !== $coreField->is_delete) {
                            array_push($diffIsDeleteStatusCoreField, $oldCoreField->id);
                        }
                    }
                }
            }

            foreach ($oldCustomFields as $oldCustomField) {
                foreach ($customFields as $customField) {
                    if ($customField->id == $oldCustomField->id) {
                        if ($oldCustomField->is_delete !== $customField->is_delete) {
                            array_push($diffIsDeleteStatusCustomField, $oldCustomField->id);
                        }
                    }
                }
            }

            $deleteStatusDiffIds = array_merge($diffIsDeleteStatusCoreField, $diffIsDeleteStatusCustomField);


            $customFieldsFromPsAdmin = $this->customizeUiService->getAll(null, null, 1, null, null, null, null, null, null, null, $deleteStatusDiffIds);
            $coreFieldsFromPsAdmin = $this->coreFieldFilterSettingService->getCoreFields(null, null, null, 1, null, $deleteStatusDiffIds);

            if ($coreFieldsFromPsAdmin->isNotEmpty()) {
                foreach ($coreFieldsFromPsAdmin as $coreFieldFromPsAdmin) {
                    foreach ($coreFields as $coreFieldFromBuilder) {
                        if ($coreFieldFromBuilder->id == $coreFieldFromPsAdmin->id) {
                            $coreFieldFromPsAdmin->is_delete = $coreFieldFromBuilder->is_delete;
                            $coreFieldFromPsAdmin->update();
                        }
                    }
                }
            }

            if ($customFieldsFromPsAdmin->isNotEmpty()) {
                foreach ($customFieldsFromPsAdmin as $customFieldFromPsAdmin) {
                    foreach ($customFields as $customFieldFromBuilder) {
                        if ($customFieldFromBuilder->id == $customFieldFromPsAdmin->id) {
                            $customFieldFromPsAdmin->is_delete = $customFieldFromBuilder->is_delete;
                            $customFieldFromPsAdmin->update();
                        }
                    }
                }
            }

            // if there have new table, will save
            if ($newTableIds->isNotEmpty()) {
                $newTables = collect($tables)->whereIn("id", $newTableIds);
                foreach ($newTables as $tableObj) {
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

            // if there have new core_key_type, will save
            if ($newCoreKeyTypeIds->isNotEmpty()) {
                $newCoreKeyTypes = collect($coreKeyTypes)->whereIn("id", $newCoreKeyTypeIds);
                foreach ($newCoreKeyTypes as $coreKeyTypeObj) {
                    $coreKeyType = new CoreKeyType();
                    $coreKeyType->id = $coreKeyTypeObj->id;
                    $coreKeyType->name = $coreKeyTypeObj->name;
                    $coreKeyType->description = $coreKeyTypeObj->description;
                    $coreKeyType->code = $coreKeyTypeObj->code;
                    $coreKeyType->client_code = $coreKeyTypeObj->client_code;
                    $coreKeyType->added_user_id = Auth::id();
                    $coreKeyType->save();
                }
            }

            // if there have diff table, will update
            $diffTableIdsArr = [];

            foreach ($oldTables as $oldTable) {
                foreach ($tables as $table) {
                    if ($oldTable->id == $table->id) {
                        if ($oldTable->is_only_for_core_field !== $table->is_only_for_core_field) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->description !== $table->description) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->core_key_type_id !== $table->core_key_type_id) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        } else if ($oldTable->table_used_type_id !== $table->table_used_type_id) {
                            array_push($diffTableIdsArr, $oldTable->id);
                        }
                    }
                }
            }

            if (collect($diffTableIdsArr)->isNotEmpty()) {
                $newTables = collect($tables)->whereIn("id", $diffTableIdsArr);
                foreach ($newTables as $tableObj) {
                    $table = $this->getTable($tableObj->id);
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


            // if there have new core field, will save
            if ($newCoreFieldIds->isNotEmpty()) {
                $newCoreFields = collect($coreFields)->whereIn("id", $newCoreFieldIds);
                // echo json_encode($newCoreFields);exit;
                foreach ($newCoreFields as $newCoreField) {
                    // save in core_field_filter_settings table
                    $coreFieldFilterSetting = new CoreField();
                    $coreFieldFilterSetting->id = $newCoreField->id;
                    $coreFieldFilterSetting->table_id = $newCoreField->table_id;
                    $coreFieldFilterSetting->project_name = $newCoreField->project_name;
                    $coreFieldFilterSetting->project_id = $newCoreField->project_id;
                    $coreFieldFilterSetting->label_name = $newCoreField->label_name;
                    $coreFieldFilterSetting->module_name = $newCoreField->module_name;
                    $coreFieldFilterSetting->base_module_name = $newCoreField->base_module_name;
                    $coreFieldFilterSetting->field_name = $newCoreField->field_name;
                    $coreFieldFilterSetting->placeholder = $newCoreField->placeholder;
                    $coreFieldFilterSetting->data_type = $newCoreField->data_type;
                    $coreFieldFilterSetting->is_delete = $newCoreField->is_delete;
                    $coreFieldFilterSetting->enable = $newCoreField->enable;
                    $coreFieldFilterSetting->mandatory = $newCoreField->mandatory;
                    $coreFieldFilterSetting->is_show_sorting = $newCoreField->is_show_sorting;
                    $coreFieldFilterSetting->ordering = $newCoreField->ordering;
                    $coreFieldFilterSetting->is_show_in_filter = $newCoreField->is_show_in_filter;
                    $coreFieldFilterSetting->is_include_in_hideshow = $newCoreField->is_include_in_hideshow;
                    $coreFieldFilterSetting->is_show = $newCoreField->is_show;
                    $coreFieldFilterSetting->is_core_field = 1;
                    $coreFieldFilterSetting->permission_for_enable_disable = $newCoreField->permission_for_enable_disable;
                    $coreFieldFilterSetting->permission_for_delete = $newCoreField->permission_for_delete;
                    $coreFieldFilterSetting->permission_for_mandatory = $newCoreField->permission_for_mandatory;
                    $coreFieldFilterSetting->added_user_id = Auth::id();
                    $coreFieldFilterSetting->save();

                    if ($newCoreField->is_include_in_hideshow == 1) {
                        // save in screen_display_ui_settings
                        $screenDisplayUiSetting = new DynamicColumnVisibility();
                        $screenDisplayUiSetting->module_name = $newCoreField->module_name;
                        $screenDisplayUiSetting->key = $newCoreField->field_name;
                        $screenDisplayUiSetting->is_show = $newCoreField->is_show;
                        $screenDisplayUiSetting->added_user_id = Auth::id();

                        $screenDisplayUiSetting->save();
                    }

                    // $languages = $this->languageService->getLanguages();
                    // foreach ($languages as $language){
                    //     foreach ($languageStrings as $languageStringObj) {
                    //         if ($languageStringObj->key == $newCoreField->label_name){
                    //             // save in language_strings table
                    //             $languageString = new LanguageString();
                    //             $languageString->language_id = $language->id;
                    //             $languageString->key = $languageStringObj->key;
                    //             $languageString->value = $languageStringObj->value;
                    //             $languageString->added_user_id = Auth::id();
                    //             $languageString->save();
                    //         }

                    //         if ($languageStringObj->key == $newCoreField->placeholder){
                    //             // save in language_strings table
                    //             $languageString = new LanguageString();
                    //             $languageString->language_id = $language->id;
                    //             $languageString->key = $languageStringObj->key;
                    //             $languageString->value = $languageStringObj->value;
                    //             $languageString->added_user_id = Auth::id();
                    //             $languageString->save();
                    //         }
                    //     }
                    // }

                }
            }

            $customFieldIds = collect($customFields)->pluck("id");
            $oldCustomFieldIds = $oldCustomFields->pluck("id");

            $newCustomFieldIds = $customFieldIds->diff($oldCustomFieldIds);
            // if there have new custom field, will save
            DB::beginTransaction();
            try {
                if ($newCustomFieldIds->isNotEmpty()) {
                    $newCustomFields = collect($customFields)->whereIn("id", $newCustomFieldIds);
                    foreach ($newCustomFields as $newCustomField) {
                        //                        return $newCustomField;
                        // save in core_field_filter_settings table
                        $customizeUi = new CustomField();
                        $customizeUi->id  = $newCustomField->id;
                        $customizeUi->table_id  = $newCustomField->table_id;
                        $customizeUi->project_name = $newCustomField->project_name;
                        $customizeUi->project_id = $newCustomField->project_id;
                        $customizeUi->name = $newCustomField->name;
                        $customizeUi->placeholder = $newCustomField->placeholder;
                        $customizeUi->ui_type_id = $newCustomField->ui_type_id;
                        $customizeUi->core_keys_id = $newCustomField->core_keys_id;
                        $customizeUi->is_delete = $newCustomField->is_delete;
                        $customizeUi->data_type = $newCustomField->data_type;
                        $customizeUi->module_name = $newCustomField->module_name;
                        $customizeUi->base_module_name = $newCustomField->base_module_name;
                        $customizeUi->enable = $newCustomField->enable;
                        $customizeUi->mandatory = $newCustomField->mandatory;
                        $customizeUi->is_show_sorting = $newCustomField->is_show_sorting;
                        $customizeUi->ordering = $newCustomField->ordering;
                        $customizeUi->is_show_in_filter = $newCustomField->is_show_in_filter;
                        $customizeUi->is_include_in_hideshow = $newCustomField->is_include_in_hideshow;
                        $customizeUi->is_show = $newCustomField->is_show;
                        $customizeUi->is_core_field = 0;
                        $customizeUi->permission_for_enable_disable = $newCustomField->permission_for_enable_disable;
                        $customizeUi->permission_for_delete = $newCustomField->permission_for_delete;
                        $customizeUi->permission_for_mandatory = $newCustomField->permission_for_mandatory;
                        $customizeUi->added_user_id  = Auth::id();
                        $customizeUi->save();

                        if ($newCustomField->is_include_in_hideshow == 1) {
                            // save in screen_display_ui_setting
                            $screenDisplayUiSetting = new DynamicColumnVisibility();
                            $screenDisplayUiSetting->module_name = $newCustomField->module_name;
                            $screenDisplayUiSetting->key = $newCustomField->core_keys_id;
                            $screenDisplayUiSetting->is_show = $newCustomField->is_show;
                            $screenDisplayUiSetting->added_user_id = Auth::id();
                            $screenDisplayUiSetting->save();
                        }

                        // $languages = $this->languageService->getLanguages();
                        // foreach ($languages as $language){
                        //     foreach ($languageStrings as $languageStringObj) {
                        //         if ($languageStringObj->key == $newCustomField->name){
                        //             // save in language_strings table
                        //             $languageString = new LanguageString();
                        //             $languageString->language_id = $language->id;
                        //             $languageString->key = $languageStringObj->key;
                        //             $languageString->value = $languageStringObj->value;
                        //             $languageString->added_user_id = Auth::id();
                        //             $languageString->save();
                        //         }

                        //         if ($languageStringObj->key == $newCustomField->placeholder){
                        //             // save in language_strings table
                        //             $languageString = new LanguageString();
                        //             $languageString->language_id = $language->id;
                        //             $languageString->key = $languageStringObj->key;
                        //             $languageString->value = $languageStringObj->value;
                        //             $languageString->added_user_id = Auth::id();
                        //             $languageString->save();
                        //         }
                        //     }
                        // }

                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                //                return $e->getMessage();
            }

            foreach ($oldCoreFields as $oldCoreField) {
                foreach ($coreFields as $coreField) {
                    if ($coreField->id == $oldCoreField->id) {
                        if ($oldCoreField->mandatory !== $coreField->mandatory || $oldCoreField->is_include_in_hideshow !== $coreField->is_include_in_hideshow || $oldCoreField->is_show !== $coreField->is_show || $oldCoreField->enable !== $coreField->enable || $oldCoreField->ordering !== $coreField->ordering || $oldCoreField->is_show_in_filter !== $coreField->is_show_in_filter || $oldCoreField->is_show_sorting !== $coreField->is_show_sorting) {
                            array_push($diffCoreFields, $oldCoreField->id);
                        }
                    }
                }
            }

            foreach ($oldCustomFields as $oldCustomField) {
                foreach ($customFields as $customField) {
                    if ($customField->id == $oldCustomField->id) {
                        if ($oldCustomField->mandatory !== $customField->mandatory || $oldCustomField->is_include_in_hideshow !== $customField->is_include_in_hideshow || $oldCustomField->is_show !== $customField->is_show || $oldCustomField->enable !== $customField->enable || $oldCustomField->ordering !== $customField->ordering || $oldCustomField->is_show_in_filter !== $customField->is_show_in_filter || $oldCustomField->is_show_sorting !== $customField->is_show_sorting) {
                            array_push($diffCustomFields, $oldCustomField->id);
                        }
                    }
                }
            }

            // if (collect($diffCustomFields)->isEmpty() && collect($diffCoreFields)->isEmpty()) {
            //     $languages = $this->languageService->getLanguages();
            //     foreach ($languages as $language) {
            //         // update lang json files
            //         $fileName = $language->symbol . '.json';
            //         $lang_str = $this->languageStringService->getLanguageStrings($language->id);
            //         generateLangStrJson($fileName, $lang_str);
            //     }
            // }

        }
        $mergedDiffIds = array_merge($diffCoreFields, $diffCustomFields);

        $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings);

        if (collect($mergedDiffIds)->isEmpty()) {

            // $needRefresh = $this->languageStringService->importAllLanguageStrings($languageStrings);

            $msg = "Import success";
            $dataArr = [
                "importSuccessMsg" => $msg,
                'needRefresh' => $needRefresh,
                "tables" => $oldTablesWithPag,
                // "recentImportedFileName" => $fileName
            ];
            return $dataArr;
        }
        $dataArr = [
            "tables" => $oldTablesWithPag,
            "diffIds" => $mergedDiffIds,
            // "recentImportedFileName" => $fileName
        ];

        // clear data From psx_updater_data table
        DB::table('psx_updater_data')->truncate();

        return $dataArr;
    }

    // public function syncTableFieldsToBuilder()
    // {

        // $customFields = $this->customizeUiService->getCustomFields(null, null, 1);
        // $coreFields = $this->coreFieldFilterSettingService->getCoreFields(null, null, "label_name as name", 1);
        // // dd($coreFields);
        // // dd($tables,$coreKeyTypes,$customFields,$coreFields);

        // $clientFields = [];
        // //merge core and custom fields
        // foreach($coreFields as $core){
        //     array_push($clientFields,$core);
        // }
        // foreach($customFields as $customFields){
        //     array_push($clientFields,$customFields);
        // }

        // $data = ['clientData' => json_encode($clientFields)];

        // dd($dataArr);
        // $data = $this->customizeUiService->export();
        // $response = postHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::syncToBuilder, null, $data);

        // return $response;
    // }

    public function doReplaceFromBuilder()
    {
        try {

            $sync_project = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getNextProjectJson, "&api_key=" . getApiKey());

            $project = Project::first();

            if ($sync_project->project->base_project_id == $project->base_project_id) {
                $tableService = $this->handleProjectNotSameBuilderSync($sync_project);
                if (isset($tableService['success'])) {
                    // Update Builder App Info Cache
                    $buildAppInfoCache = new \stdClass();
                    $buildAppInfoCache->isProjectChanged = 0;
                    updateBuilderAppInfoCache($buildAppInfoCache);
                }
                if (empty($tableService['success']) || !empty($sync_project->status)) {
                    $dataArr = [
                        "replace_status" => 'error',
                        "replace_message" => __('core__be_error_replace')
                    ];
                    return $dataArr;
                }
            } else {
                $dataArr = [
                    "replace_status" => 'error',
                    "replace_message" => __('base_proj_not_same')
                ];
                return $dataArr;
            }
        } catch (\Throwable $e) {
            $dataArr = [
                "replace_status" => 'error',
                "replace_message" => $e->getMessage()
            ];
            return $dataArr;
        }
        $dataArr = [
            "replace_status" => 'success',
            "replace_message" => __('core__be_replace_success_note')
        ];
        return $dataArr;
    }

    public function handleProjectNotSameBuilderSync($request)
    {

        $project = $request->project;
        $projectIdFromDB = getProjectId();
        $projectIdFromApi = $project->id;

        if ($projectIdFromApi !== $projectIdFromDB) {
            // Project::truncate();

            // read json file that come from zip file
            $customFields = $request->custom_field_infos;
            $coreFields = $request->core_field_infos;
            $tables = $request->tables;
            $coreKeyTypes = $request->core_key_types;
            $languageStrings = [];

            DB::beginTransaction();
            try {

                // save or update in projects table
                Project::updateOrCreate(
                    ['base_project_id' =>  $project->base_project_id],
                    [
                        'id' => $project->id,
                        'project_name' => $project->project_name,
                        'project_code' => $project->project_code,
                        'project_url' => $project->project_url,
                        'first_time_sync' => 1,
                        'added_user_id' => Auth::id()
                    ]
                );

                CustomField::latest()->update(['project_id' => $projectIdFromApi]);

                CoreField::latest()->update(['project_id' => $projectIdFromApi]);

                $needRefresh = true;
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return $e->getMessage() . "<br>" . $e->getLine();
            }

            $activatedFileName = ActivatedFileName::first();
            $activatedFileName->is_imported = 1;
            $activatedFileName->update();
        } else {
            $needRefresh = null;
        }



        $msg = "Import success";
        $dataArr = [
            "importSuccessMsg" => $msg,
            'needRefresh' => $needRefresh,
            // "recentImportedFileName" => $fileName
            "success" => "Import Success"
        ];
        return $dataArr;
        // return 'replace success';
    }
}
