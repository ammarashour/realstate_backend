<?php

namespace Modules\Core\Http\Requests\Localization;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreMobileLanguageRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::mobileLanguage);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::mobileLanguage);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = [
            [
                'fieldName' => 'symbol',
                'rules' => 'required|unique:psx_mobile_languages,symbol',
            ],
            [
                'fieldName' => 'name',
                'rules' => 'required|unique:psx_mobile_languages,name,',
            ],
            [
                'fieldName' => 'languageCode',
                'rules' => 'required|unique:psx_mobile_languages,language_code',
            ],
            [
                'fieldName' => 'countryCode',
                'rules' => 'required|unique:psx_mobile_languages,country_code',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::language, $this->language_relation);

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
