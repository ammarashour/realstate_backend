<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class StoreLandingPageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'cover' => 'nullable|sometimes|image',
            'logo' => 'nullable|sometimes|image',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $conds = [
                'module_name' => Constants::landingPage,
                'enable' => 1,
                'mandatory' => 1,
                'is_core_field' => 1,
            ];
            $core_headers = CoreField::where($conds)->get();

            $core_fields = $validator->getData() ?? '';

            foreach ($core_headers as $core_header) {
                if ($core_header->field_name == 'landing-cover') {
                    if (array_key_exists('cover', $core_fields) && empty($core_fields['cover'])) {
                        $replace = [];
                        $replace['attribute'] = 'cover';
                        $validator->errors()->add('cover', __('validation__required', $replace));
                    }
                }
                if ($core_header->field_name == 'landing-icon') {
                    if (array_key_exists('logo', $core_fields) && empty($core_fields['logo'])) {
                        $replace = [];
                        $replace['attribute'] = 'logo';
                        $validator->errors()->add('logo', __('validation__required', $replace));
                    }
                }
            }
        });
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
}
