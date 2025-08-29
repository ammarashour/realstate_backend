<?php

namespace Modules\Core\Http\Requests\User;

use Illuminate\Support\Arr;
use App\Exceptions\PsApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreRatingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "from_user_id" => "required|exists:users,id",
            "to_user_id" => "required|exists:users,id",
            "rating" => "required",
            "title" => "nullable",
            "description" => "nullable",
            "transaction_header_id" => "nullable",
            'type' => 'required',
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
            'from_user_id' => 'From User',
            'to_user_id' => 'To User',
            'rating' => 'Rating',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'transaction_header_id' => 'Transaction Header'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );

    }
}
