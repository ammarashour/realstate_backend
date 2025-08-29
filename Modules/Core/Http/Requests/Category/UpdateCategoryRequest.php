<?php

namespace Modules\Core\Http\Requests\Category;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;

class UpdateCategoryRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::category, $this->category_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::category);
        $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            // array(
            //     'fieldName' => 'name',
            //     'rules' => 'nullable',
            // ),
            array(
                'fieldName' => 'nameForm',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ordering',
                'rules' => 'nullable|integer|min:1',
            ),
            array(
                'fieldName' => 'cover_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'icon_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'cat_photo',
                'rules' => 'required|sometimes|image',
            ),
            array(
                'fieldName' => 'cat_icon',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::category, $this->category_relation);

        $coreFieldAttributeArr = [
            'cat_photo' => "Category Photo",
            'cat_icon' => "Category Icon",
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
