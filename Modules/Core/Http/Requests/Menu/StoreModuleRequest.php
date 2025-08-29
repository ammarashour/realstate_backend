<?php

namespace Modules\Core\Http\Requests\Menu;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Rules\HasRouteName;
use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreModuleRequest extends FormRequest
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
         $errors = validateForCustomField(Constants::module, $this->module_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::module);
         $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

         $validationRules = array(
             array(
                 'fieldName' => 'title',
                 'rules' => 'required|min:3|unique:psx_modules,title',
             ),
             array(
                 'fieldName' => 'route_name',
                 'rules' => ['required', new HasRouteName(), 'unique:psx_modules,route_name'],
             ),
             array(
                 'fieldName' => 'lang_key',
                 'rules' => 'required',
             ),
             array(
                 'fieldName' => 'is_not_from_sidebar',
                 'rules' => 'nullable',
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
         $validationArr = handleValidation($errors, $coreFields, $validationRules);
         return $validationArr;

     }

     public function attributes()
     {
         $customFieldAttributeArr = handleCFAttrForValidation(Constants::module, $this->module_relation);

         $coreFieldAttributeArr = [
             'route_name' => "Route Name",
             'lang_key' => "Language Key",
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
