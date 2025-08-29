<?php

namespace Modules\Core\Http\Requests\Item;

use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class VideoUploadItemApiRequestV2 extends FormRequest
{
    public function rules()
    {
        return [
            'item_id' => 'required|exists:psx_items,id',
            'img_id' => 'nullable|exists:psx_core_images,id',
            'video' => 'required',
            'total_chunks' => 'required',
            'file_size' => 'required',
            'chunk_no' => 'required',
            'name' => 'required',
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
