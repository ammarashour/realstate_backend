<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|unique:psx_products,title,'.$this->route('product')->id,
            'description' => 'required|min:10',
            'photo' => 'required|file|mimes:jpg,png,jpeg',
            'category_id' => 'required|exists:psx_sub_categories,id'
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
