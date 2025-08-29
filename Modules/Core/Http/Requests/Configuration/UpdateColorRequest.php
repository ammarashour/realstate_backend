<?php

namespace Modules\Core\Http\Requests\Configuration;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;

class UpdateColorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function __construct(protected CoreFieldServiceInterface $coreFieldService)
    {}

    public function rules()
    {
        // prepare for custom field validation
        $errors = validateForCustomField(Constants::color, $this->color_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::color);
        $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            array(
                'fieldName' => 'key',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'value',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'title',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_light_color',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_dark_color',
                'rules' => 'nullable',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::backendSetting, $this->backend_setting_relation);

        $coreFieldAttributeArr = [
            'key' => "Key",
            'value' => "Value",
            'title' => "Title",
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
