<?php

namespace Modules\Core\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;


class StoreVendorBranchRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::vendorBranches, $this->city_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::vendorBranches);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            [
                'fieldName' => 'name',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'vendor_id',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'email',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'phone',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'address',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'description',
                'rules' => 'required',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::locationCity, $this->city_relation);

        $coreFieldAttributeArr = [
            'name' => 'Name',
            'vendor_id' => 'Vendor ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Email',
            'description' => 'Description',
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