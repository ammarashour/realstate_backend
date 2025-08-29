<?php

namespace Modules\Core\Http\Requests\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'name' => 'required|min:3|unique:psx_shippings,name,',
            'price' => 'nullable',
            'days' => 'nullable',
            'status' => 'nullable',
            'title' => 'required|min:3|unique:psx_packages,title'
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
