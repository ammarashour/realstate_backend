<?php

namespace Modules\Core\Http\Requests\Category;

use API;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Entities\Utilities\CoreField;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreSubcategoryRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::subcategory, $this->subcategory_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::subcategory);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = array(
            array(
                'fieldName' => 'name',
                'rules' => 'required|min:3|unique:psx_subcategories',
            ),
            array(
                'fieldName' => 'nameForm',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ordering',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'category_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'sub_cat_photo',
                'rules' => 'required|sometimes|image',
            ),
            array(
                'fieldName' => 'sub_cat_icon',
                'rules' => 'required|sometimes|image',
            ),
            array(
                'fieldName' => 'status',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'added_user_id',
                'rules' => 'nullable',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;
    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::subcategory, $this->subcategory_relation);

        $coreFieldAttributeArr = [
            'category_id' => "Category",
            'name' => "Subcategory",
            'sub_cat_photo' => "Subcategory Photo",
            'sub_cat_icon' => "Subcategory Icon",
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
