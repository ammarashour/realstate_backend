<?php

namespace Modules\Core\Http\Requests\Localization;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateMobileLanguageStringRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::mobileLanguageString);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::mobileLanguageString);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            
            [
                'fieldName' => 'value',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'mobile_language_id',
                'rules' => 'nullable',
            ],
        ];

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::mobileLanguageString, $this->language_relation);

        $coreFieldAttributeArr = [
            'name' => 'Name',
            'symbol' => 'Symbol'
        ];
        $attributeArr = array_merge($coreFieldAttributeArr, $customFieldAttributeArr);

        return $attributeArr;
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
