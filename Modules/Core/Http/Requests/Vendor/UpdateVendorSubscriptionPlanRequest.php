<?php

namespace Modules\Core\Http\Requests\Vendor;

use App\Rules\CheckUniqueValueColForPaymentInfo;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateVendorSubscriptionPlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function __construct(protected CoreFieldFilterSettingService $coreFieldFilterSettingService){} 

    public function rules()
    {
        $errors = validateForCustomField(Constants::payment, $this->payment_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::payment);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);

        $validationRules = [
            [
                'fieldName' => 'in_app_purchase_prd_id',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'core_keys_id',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'title',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'sales_price',
                'rules' => 'required|numeric|min:1',
            ],
            [
                'fieldName' => 'discount_price',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'duration',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'currency_id',
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
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::payment, $this->payment_relation);

        $coreFieldAttributeArr = [
            'in_app_purchase_prd_id' => 'In App Purchase Product ID',
            'core_keys_id' => 'core_keys_id',
            'title' => 'Titile',
            'sales_price' => 'Sales Price',
            'discount_price' => 'Discount Price',
            'duration' => 'Duration',
            'currency_id' => 'Currency ID',
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
