<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class VideoUploadItemApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'item_id' => 'required|exists:psx_items,id',
            'img_id' => 'nullable|exists:psx_core_images,id',
            'video' => 'required',
            // 'offset' => 'required|integer|min:0',  // Ensure offset is provided and is a valid integer
            // 'totalSize' => 'required|integer|min:1', // Ensure totalSize is provided and is valid
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
