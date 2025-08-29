<?php

namespace Modules\Core\Http\Requests\User;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class IsSoftDeletePushNotificationReadUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'noti_id' => 'required',
            'user_id' => 'required',
            'device_token' => 'required',
            'noti_type' => 'required',
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
