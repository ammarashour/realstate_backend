<?php

namespace Modules\Core\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|unique:psx_transaction_statuses,title,',
            'color_value' => 'required',
            'ordering' => 'nullable',
            'start_stage' => 'nullable',
            'final_stage' => 'nullable',
            'is_optional' => 'nullable',
            'is_refundable' => 'nullable',
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
