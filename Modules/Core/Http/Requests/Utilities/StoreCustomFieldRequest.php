<?php

namespace Modules\Core\Http\Requests\Utilities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreCustomFieldRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $coreFieldFilterSettingService;

    public function __construct(CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
    }

    public function rules()
    {
        // prepare for custom field validation
        $errors = validateForCustomField(Constants::tableField, $this->custom_field_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::tableField);

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = [
            [
                'fieldName' => 'name',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'nameForm',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'placeholderForm',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'name_str',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'placeholder_str',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'placeholder',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'ui_type_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'core_keys_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'mandatory',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_show_sorting',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_show_in_filter',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'ordering',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'enable',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_delete',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'module_name',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'data_type',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'table_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'project_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'project_name',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'base_module_name',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_include_in_hideshow',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_show',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_core_field',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'permission_for_enable_disable',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'permission_for_delete',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'permission_for_mandatory',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'category_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'added_date',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'added_user_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'updated_date',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'updated_user_id',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'updated_flag',
                'rules' => 'nullable',
            ],
        ];

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
