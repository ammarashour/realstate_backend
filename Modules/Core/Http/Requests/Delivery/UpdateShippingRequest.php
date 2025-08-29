<?php

namespace Modules\Core\Http\Requests\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_id' => 'required',
            'name' => 'required|min:3|unique:psx_shippings,name,'.$this->route('shipping')->id,
            'price' => 'required',
            'days' => 'nullable',
            'status' => 'nullable',
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
