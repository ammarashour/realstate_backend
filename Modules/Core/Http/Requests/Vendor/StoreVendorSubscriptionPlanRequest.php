<?php

namespace Modules\Core\Http\Requests\Vendor;

use App\Rules\CheckUniqueValueColForPaymentInfo;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StoreVendorSubscriptionPlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'in_app_purchase_prd_id' => 'required',
            'title' => ["required", new CheckUniqueValueColForPaymentInfo(null, Constants::vendorSubscriptionPlanPaymentId)],
            'sale_price' => 'required',
            'discount_price' => 'required',
            'duration' => 'required',
            'currency_id' => 'required',
            'is_most_popular_plan' => 'nullable',
            'status' => 'nullable'
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
