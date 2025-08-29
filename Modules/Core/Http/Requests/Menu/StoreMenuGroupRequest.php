<?php

namespace Modules\Core\Http\Requests\Menu;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreMenuGroupRequest extends FormRequest
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
         $errors = validateForCustomField(Constants::coreMenuGroup, $this->menu_group_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::coreMenuGroup);
         $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

         $validationRules = array(
             array(
                 'fieldName' => 'group_name',
                 'rules' => 'required|min:3|unique:psx_core_menu_groups,group_name',
             ),
             array(
                 'fieldName' => 'group_lang_key',
                 'rules' => 'required|unique:psx_core_menu_groups,group_lang_key',
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
         $customFieldAttributeArr = handleCFAttrForValidation(Constants::coreMenuGroup, $this->menu_group_relation);

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
