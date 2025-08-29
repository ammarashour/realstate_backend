<?php

namespace Modules\Core\Http\Requests\Authorization;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class RegisterPushNotiTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required',
            'device_token' => 'required',
            'platform_name' => 'required',
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
