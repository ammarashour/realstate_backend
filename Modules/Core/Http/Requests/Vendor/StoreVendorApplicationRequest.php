<?php

namespace Modules\Core\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;

class StoreVendorApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'store_name' => 'required|min:3|unique:psx_vendors,name,',
            'cover_letter' => 'required',
            'document' => 'required|mimes:pdf,zip',
            'currency_id' => 'required',
            'id' => 'nullable',
            'language_symbol' => 'nullable',
            'login_user_id' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );

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

    public function messages() {
        return [
            'document.mimes' => 'Please upload the allowed file type.',
            'store_name.unique' => 'The vendor name has already been taken.'
        ];
    }
}
