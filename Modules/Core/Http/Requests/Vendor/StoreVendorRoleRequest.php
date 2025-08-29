<?php

namespace Modules\Core\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|unique:psx_vendor_roles,name',
            'description' => 'nullable',
            'permissions' => 'nullable',
            'permissionObj' => 'nullable'
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
}
