<?php

namespace Modules\StoreFront\VendorPanel\Http\Requests;

use App\Rules\CheckRequiredForVendorDeliveryCharge;
use App\Rules\CheckVendorDeliveryCharge;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeliverySettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'delivery_charges' => [new CheckRequiredForVendorDeliveryCharge($this->delivery_setting), new CheckVendorDeliveryCharge($this->delivery_setting)],
        ];
    }

    public function messages()
    {
        $attributeArr = [
            'delivery_charges.numeric' => "Delivery charges field must be numeric.",
        ];

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
