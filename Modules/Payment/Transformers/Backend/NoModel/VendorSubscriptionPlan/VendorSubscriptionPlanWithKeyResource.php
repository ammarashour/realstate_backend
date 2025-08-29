<?php

namespace Modules\Payment\Transformers\Backend\NoModel\VendorSubscriptionPlan;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;


class VendorSubscriptionPlanWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $currency = '';
        $currencySymbol = '';
        if ($this->currency_id) {
            $availableCurrency = AvailableCurrency::find($this->currency_id);
            $currency = $availableCurrency->currency_short_form;
            $currencySymbol = $availableCurrency->currency_symbol;
        }
        return [
            'id' => $this->id,
            'in_app_purchase_prd_id' => !empty($this->core_key) ? $this->core_key->name : '',
            'core_keys_id' => $this->core_keys_id,
            'value' => $this->value,
            'duration' => convertMonthFromStringToNumber($this->duration),
            'discount_price' => !$this->discount_price ? "Unavailable" : $this->discount_price,
            'sale_price' => $this->sale_price,
            'is_most_popular_plan' => $this->is_most_popular_plan,
            'status' => $this->status,
            'currency_id' => $currency,
            'currency_symbol' => $currencySymbol,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::vendorSubscriptionPlanModule, Auth::id())
        ];
    }
}
