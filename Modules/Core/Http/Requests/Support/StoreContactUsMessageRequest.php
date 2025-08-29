<?php

namespace Modules\Core\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;

class StoreContactUsMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contact_name' => 'required',
            'contact_email' => 'required',
            'contact_message' => 'required',
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
}
