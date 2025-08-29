<?php

namespace Modules\StoreFront\VendorPanel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CustomizeUi;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateVendorRequest extends FormRequest
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
        $module = Constants::vendor;
        $customFieldData = $this->vendor_relation;

        // prepare for custom field validation
        $errors = validateForCustomField($module, $customFieldData);

        // prepare for core field validation
        $cond = prepareCoreFieldValidationConds($module);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);
        $validations = array(
            array(
                'fieldName' => 'name',
                'rules' => 'required|unique:psx_vendors,name,'.request()->route('vendor_info'),
            ),
            array(
                'fieldName' => 'email',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'phone',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'address',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'description',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'website',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'facebook',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'instagram',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'logo',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'banner_1',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'banner_2',
                'rules' => 'nullable|sometimes|image',
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
        $validationArr = handleValidation($errors, $coreFields, $validations);
        return $validationArr;

    }

    public function attributes()
    {

        $customFieldAttributeArr = handleCFAttrForValidation(Constants::vendor, $this->vendor_relation);

        $coreFieldAttributeArr = [
            'name' => "Vendor Name",
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
