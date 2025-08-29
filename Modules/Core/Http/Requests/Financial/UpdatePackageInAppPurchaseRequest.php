<?php

namespace Modules\Core\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Utilities\CoreFieldService;

class UpdatePackageInAppPurchaseRequest extends FormRequest
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
            'count' => 'required',
            'price' => 'required',
            'type' => 'required'
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
