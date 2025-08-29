<?php

namespace Modules\Core\Http\Requests\Menu;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreCoreMenuRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::coreModule, $this->menu_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::coreModule);
        $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            array(
                'fieldName' => 'module_name',
                'rules' => 'required|min:3|unique:psx_core_menus,module_name',
            ),
            array(
                'fieldName' => 'core_sub_menu_group_id',
                'rules' => 'required',
            ),
            array(
                'fieldName' => 'module_desc',
                'rules' => 'required|unique:psx_core_menus,module_desc',
            ),
            array(
                'fieldName' => 'module_lang_key',
                'rules' => 'required|unique:psx_core_menus,module_lang_key',
            ),
            array(
                'fieldName' => 'module_id',
                'rules' => 'required|unique:psx_core_menus,module_id',
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
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::coreModule, $this->menu_relation);

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
