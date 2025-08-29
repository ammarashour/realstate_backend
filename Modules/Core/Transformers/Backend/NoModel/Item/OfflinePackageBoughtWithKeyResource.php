<?php

namespace Modules\Core\Transformers\Backend\NoModel\Item;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;


class OfflinePackageBoughtWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $selectedIds = explode(',', $this->package->core_keys_id);

        $objs  = PaymentAttribute::whereIn("core_keys_id", $selectedIds)->get();

        $attributes = $objs->map(function ($key, $value) {
            if ($key['attribute_key'] == "currency_id") {
                $currency  = AvailableCurrency::where("id", $key['attribute_value'])->first();
                return [
                    'currency_symbol' => $currency->currency_symbol,
                    'currency_short_form' => $currency->currency_short_form,
                ];
            }
            return [
                $key['attribute_key'] => $key['attribute_value']
            ];
        })->collapse();

        $data = $attributes;

        if (count($data) == 0) {
            $empty = new \stdClass;
            $empty->type = "";
            $empty->count = "";
            $empty->price = "";
            $empty->status = "";
            $empty->currency_symbol = "";
            $empty->currency_short_form = "";
            $empty->is_empty_object = 1;
        }


        return [
            'id' => isset($this->id) ? (string)$this->id : '',
            'user_id' => isset($this->user_id) ? (string)$this->user_id : '',
            'user_name' => isset($this->user_name) ? (string)$this->user_name : '',
            'value' => isset($this->value) ? (string)$this->value : '',
            'post_count' => $data['count'],
            'package_id' => isset($this->package_id) ? (string)$this->package_id : '',
            'payment_method' => isset($this->payment_method) ? (string)$this->payment_method : '',
            'price' => isset($this->price) ? (string)$this->price : '',
            'razor_id' => isset($this->razor_id) ? (string)$this->razor_id : '',
            'isPaystack' => isset($this->is_paystack) ? (string)$this->is_paystack : '',
            'status' => isset($this->status) ? (string)$this->status : '',
            'added_date' => isset($this->added_date) ? (string)$this->added_date : '',
            "user" => isset($this->user) && $this->user ? $this->user : null,
            "package" => isset($this->package) && $this->package ? $this->package : null,
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "is_empty_object" => $this->when(!isset($this->id), 1),
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::offlinePackageBoughtModule, Auth::id())
        ];
    }
}
