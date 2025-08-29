<?php

namespace Modules\Core\Http\Requests\Menu;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateSubMenuGroupRequest extends FormRequest
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
         $errors = validateForCustomField(Constants::coreSubMenuGroup, $this->sub_menu_group_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::coreSubMenuGroup);
         $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            array(
                'fieldName' => 'sub_menu_name',
                'rules' => 'required|min:3|unique:psx_core_sub_menu_groups,sub_menu_name,'.$this->sub_menu_group
            ),
            array(
                'fieldName' => 'core_menu_group_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'sub_menu_desc',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'sub_menu_lang_key',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'icon_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ordering',
                'rules' => 'integer',
            ),
            array(
                'fieldName' => 'is_dropdown',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'module_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_on_menu',
                'rules' => 'required',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::coreSubMenuGroup, $this->sub_menu_group_relation);

        $coreFieldAttributeArr = [
            'sub_menu_name' => "Sub Menu Name",
            'core_menu_group_id' => "Menu Group Id",
            'is_show_on_menu' => "Status",
            'is_dropdown' => "Has Child Menu",
            'icon_id' => "Icon",
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
