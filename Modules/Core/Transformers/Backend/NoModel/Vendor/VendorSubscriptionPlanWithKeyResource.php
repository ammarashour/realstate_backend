<?php

namespace Modules\Core\Transformers\Backend\NoModel\Vendor;

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
            'id' => (string) $this->id,
            'in_app_purchase_prd_id' => (string) !empty($this->core_key) ? $this->core_key->name : '',
            'core_keys_id' => (string) $this->core_keys_id,
            'value' => (string) $this->value,
            'duration' => convertMonthFromStringToNumber($this->duration),
            'discount_price' => (string)!$this->discount_price ? "Unavailable" : $this->discount_price,
            'sale_price' => (string) $this->sale_price,
            'is_most_popular_plan' => (string) $this->is_most_popular_plan,
            'status' => (string) $this->status,
            'currency_id' => (string) $currency,
            'currency_symbol' => (string) $currencySymbol,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_date' => (string) $this->updated_date,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::vendorSubscriptionPlanModule, Auth::id())
        ];
    }

    private function getAddedUserName()
    {
        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {
        if (empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }
}
