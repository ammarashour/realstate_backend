<?php

namespace Modules\Core\Http\Requests\Information;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StorePrivacyPolicyRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::privacyPolicy, $this->privacyPolicy_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::privacyPolicy);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            [
                'fieldName' => 'content',
                'rules' => 'required',
            ],
        ];

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::blog, $this->blog_relation);

        $coreFieldAttributeArr = [
            'content' => 'Content',
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
