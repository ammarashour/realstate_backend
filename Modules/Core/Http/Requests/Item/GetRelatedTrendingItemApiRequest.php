<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class GetRelatedTrendingItemApiRequest extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'required|exists:psx_items,id',
            'cat_id' => 'required|exists:psx_categories,id',
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
