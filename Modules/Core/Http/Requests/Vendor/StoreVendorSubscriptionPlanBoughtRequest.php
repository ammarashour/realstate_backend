<?php

namespace Modules\Core\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PsApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;

class StoreVendorSubscriptionPlanBoughtRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'required|exists:psx_vendors,id',
            'subscription_plan_id' => 'required|exists:psx_payment_infos,id',
            'payment_method' => 'required',
            'price' => 'required',
            'razor_id' => 'nullable',
            'is_paystack' => 'nullable',
            'transaction_id' => 'nullable',
            'status' => 'nullable',
            'language_symbol' => 'nullable',
            'payment_method_nonce' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new PsApiException(
            implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()))
        );

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
