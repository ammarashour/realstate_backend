<?php

namespace Modules\Core\Http\Requests\Information;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreAboutRequest extends FormRequest
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
        $module = Constants::about;
        $customFieldData = $this->about_relation;

        // prepare for custom field validation
        $errors = validateForCustomField($module, $customFieldData);

        // prepare for core field validation
        $cond = prepareCoreFieldValidationConds($module);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);
        $validations = array(
            array(
                'fieldName' => 'name',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'location_city_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'description',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'blog_photo',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'about_phone',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'added_user_id',
                'rules' => 'nullable',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validations);
        return $validationArr;
    }

    public function attributes()
    {
        $module = Constants::blog;
        $customFieldData = $this->blog_relation;

        $customFieldAttributeArr = handleCFAttrForValidation($module, $customFieldData);

        $coreFieldAttributeArr = [
            'location_city_id' => "Location City",
            'name' => "Blog Name",
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
