<?php

namespace Modules\Core\Http\Requests\Menu;

use App\Rules\HasRouteName;
use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreVendorModuleRequest extends FormRequest
{
    public function __construct(protected CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // prepare for custom field validation
        $errors = validateForCustomField(Constants::vendorModuleKey, $this->vendor_module_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::vendorModuleKey);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            [
                'fieldName' => 'title',
                'rules' => 'required|min:3|unique:psx_vendor_modules,title',
            ],
            [
                'fieldName' => 'lang_key',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'route_name',
                'rules' => ['required', new HasRouteName(), 'unique:psx_vendor_modules,route_name']
            ],
            [
                'fieldName' => 'status',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'is_not_from_sidebar',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'added_user_id',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::vendorSubMenuGroup, $this->vendor_module_relation);

        $coreFieldAttributeArr = [
            'title' => 'Title',
            'lang_key' => 'Module Language Key',
            'route_name' => 'Route Name',
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
