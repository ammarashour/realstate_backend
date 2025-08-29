<?php

namespace Modules\Core\Http\Requests\Menu;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateVendorMenuRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::vendorMenu, $this->vendor_menu_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::vendorMenu);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = array(
            array(
                'fieldName' => 'module_name',
                'rules' => 'required|min:3|unique:psx_vendor_menus,module_name,'.$this->vendor_menu,
            ),
            array(
                'fieldName' => 'core_sub_menu_group_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'module_desc',
                'rules' => 'required|unique:psx_vendor_menus,module_desc,'.$this->vendor_menu,
            ),
            array(
                'fieldName' => 'module_lang_key',
                'rules' => 'required|unique:psx_vendor_menus,module_lang_key,'.$this->vendor_menu,
            ),
            array(
                'fieldName' => 'module_id',
                'rules' => 'required|unique:psx_vendor_menus,module_id,'.$this->vendor_menu,
            ),
            array(
                'fieldName' => 'ordering',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_on_menu',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'added_user_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'old_module_id',
                'rules' => 'nullable',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::vendorMenu, $this->vendor_menu_relation);

        $coreFieldAttributeArr = [
            'module_name' => "Module Name",
            'core_sub_menu_group_id' => "Sub Menu Group",
            'module_lang_key' => "Menu Language Key",
            'module_desc' => "Menu Description",
            'module_id' => "Module",
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
