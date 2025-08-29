<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class GetGalleryListItemApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'img_parent_id' => 'required|exists:psx_items,id',
            'img_type' => 'nullable',
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
