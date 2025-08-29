<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreLocationCityRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::locationCity, $this->city_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::locationCity);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            [
                'fieldName' => 'name',
                'rules' => 'required|min:3|unique:psx_location_cities,name,',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::locationCity, $this->city_relation);

        $coreFieldAttributeArr = [
            'name' => 'city name',
            'lat' => 'latitude',
            'lng' => 'longitude',
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
