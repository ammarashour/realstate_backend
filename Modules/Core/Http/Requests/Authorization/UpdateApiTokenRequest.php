<?php

namespace Modules\Core\Http\Requests\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApiTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable', 'string', 'max:255',
            'permissions' => 'nullable',
            'status' => 'nullable'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
