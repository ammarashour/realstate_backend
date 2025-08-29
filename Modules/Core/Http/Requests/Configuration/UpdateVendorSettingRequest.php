<?php

namespace Modules\Core\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'notic_days' => 'required|numeric',
            'vendor_setting' => 'nullable',
            'vendor_subscription' => 'nullable',
            'vendor_checkout_setting' => 'nullable',
        ];
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
