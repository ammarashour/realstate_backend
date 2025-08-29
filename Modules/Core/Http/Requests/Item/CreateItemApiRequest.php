<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class CreateItemApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id' => 'nullable',
            'language_symbol' => 'required|exists:psx_languages,symbol',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );
    }

    public function authorize()
    {
        return true;
    }
}
