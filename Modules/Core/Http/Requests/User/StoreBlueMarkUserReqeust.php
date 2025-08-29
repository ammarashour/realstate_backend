<?php

namespace Modules\Core\Http\Requests\User;

use Illuminate\Support\Arr;
use App\Exceptions\PsApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreBlueMarkUserReqeust extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'note' => 'required',
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

    public function attributes()
    {
        return [
            'user_id' => 'User Id',
            'note' => 'BlueMark Note'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );

    }
}
