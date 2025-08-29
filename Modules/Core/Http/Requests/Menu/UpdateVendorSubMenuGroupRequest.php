<?php

namespace Modules\Core\Http\Requests\Menu;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateVendorSubMenuGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function __construct(protected CoreFieldFilterSettingService $coreFieldFilterSettingService)
     {

     }

     public function rules()
     {
         // prepare for custom field validation
         $errors = validateForCustomField(Constants::vendorSubMenuGroup, $this->vendor_sub_menu_group_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::vendorSubMenuGroup);
         $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
         $validationRules = [
             [
                 'fieldName' => 'sub_menu_name',
                 'rules' => 'required|min:3|unique:psx_vendor_sub_menus,sub_menu_name,'.$this->vendor_sub_menu_group,
             ],
             [
                 'fieldName' => 'core_menu_group_id',
                 'rules' => 'required',
             ],
             [
                 'fieldName' => 'sub_menu_desc',
                 'rules' => 'required',
             ],
             [
                 'fieldName' => 'sub_menu_lang_key',
                 'rules' => 'required',
             ],
             [
                 'fieldName' => 'icon_id',
                 'rules' => 'required',
             ],
             [
                 'fieldName' => 'ordering',
                 'rules' => 'integer',
             ],
             [
                 'fieldName' => 'is_show_on_menu',
                 'rules' => 'nullable',
             ],
             [
                 'fieldName' => 'is_dropdown',
                 'rules' => 'nullable',
             ],
             [
                 'fieldName' => 'module_id',
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
         $customFieldAttributeArr = handleCFAttrForValidation(Constants::vendorSubMenuGroup, $this->vendor_sub_menu_group_relation);

         $coreFieldAttributeArr = [
             'sub_menu_name' => 'Sub Menu Name',
             'core_menu_group_id' => 'Menu Group Id',
             'sub_menu_desc' => 'Sub Menu Description',
             'sub_menu_lang_key' => 'Language Key',
             'icon_id' => 'Icon',
             'ordering' => 'Ordering',
             'module_id' => 'Module',
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
