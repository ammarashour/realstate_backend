<?php

namespace Modules\Core\Http\Requests\User;

use Illuminate\Support\Arr;
use App\Exceptions\PsApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreBlockUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // protected $redirect;
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from_block_user_id' => 'required|exists:users,id',
            'to_block_user_id' => 'required|exists:users,id',
        ];
    }

    public function attributes()
    {
        return [
            'from_block_user_id' => 'From Block User',
            'to_block_user_id' => 'To Block User'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );

    }
}
