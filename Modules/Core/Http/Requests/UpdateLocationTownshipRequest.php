<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateLocationTownshipRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::locationTownship, $this->township_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::locationTownship);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = [
            [
                'fieldName' => 'name',
                'rules' => 'required|min:3',
            ],
            [
                'fieldName' => 'description',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'ordering',
                'rules' => 'nullable',
            ],
            [
                'fieldName' => 'lat',
                'rules' => 'required|numeric|max:90|min:-90',
            ],
            [
                'fieldName' => 'lng',
                'rules' => 'required|numeric|max:180|min:-180',
            ],
            [
                'fieldName' => 'location_city_id',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::locationTownship, $this->township_relation);

        $coreFieldAttributeArr = [
            'name' => 'township name',
            'lat' => 'latitude',
            'lng' => 'longitude',
            'location_city_id' => 'location city',
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
