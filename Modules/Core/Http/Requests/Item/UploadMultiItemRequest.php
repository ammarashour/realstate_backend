<?php

namespace Modules\Core\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UploadMultiItemRequest extends FormRequest
{

    protected $coreFieldFilterSettingService;
    public function __construct(CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
    }

    public function rules()
    {
        // Validate the custom fields
        $errors = [];

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::item);

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = array(
            array(
                'fieldName' => 'edit_mode',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'item_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'img_parent_id',
                'rules' => 'required',
            ),
        );

        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::item, $this->product_relation);

        $coreFieldAttributeArr = [
            'original_price.max' => "The original price must not be greater than 6 digits.",
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
