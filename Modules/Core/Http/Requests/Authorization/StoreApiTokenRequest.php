<?php

namespace Modules\Core\Http\Requests\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required', 'string', 'max:255',
            'permissions' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
