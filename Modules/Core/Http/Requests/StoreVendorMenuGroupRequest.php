<?php

namespace Modules\Core\Http\Requests;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreVendorMenuGroupRequest extends FormRequest
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
         $errors = validateForCustomField(Constants::vendorMenuGroup, $this->vendor_menu_group_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::vendorMenuGroup);
         $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

         $validationRules = array(
             array(
                 'fieldName' => 'group_name',
                 'rules' => 'required|min:3|unique:psx_vendor_menu_groups,group_name',
             ),
             array(
                 'fieldName' => 'group_lang_key',
                 'rules' => 'required',
             ),
             array(
                 'fieldName' => 'is_show_on_menu',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'is_invisible_group_name',
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
         $customFieldAttributeArr = handleCFAttrForValidation(Constants::vendorMenuGroup, $this->vendor_menu_group_relation);

         $coreFieldAttributeArr = [
             'group_name' => "Group Name",
             'group_lang_key' => "Language Key",
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
