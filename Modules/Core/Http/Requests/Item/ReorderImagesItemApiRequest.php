<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ReorderImagesItemApiRequest extends FormRequest
{

    public function rules()
    {
        $rules = [];
        
        foreach ($this->json()->all() as $index => $data) {
            $rules["{$index}.img_id"] = 'required|exists:psx_core_images,id';
            $rules["{$index}.ordering"] = 'required';
        }

        return $rules;
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
