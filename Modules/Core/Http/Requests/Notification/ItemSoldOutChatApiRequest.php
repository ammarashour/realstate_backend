<?php

namespace Modules\Core\Http\Requests\Notification;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ItemSoldOutChatApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'item_id' => 'required|exists:psx_items,id',
            'buyer_user_id' => 'required|exists:users,id',
            'seller_user_id' => 'required|exists:users,id',
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
