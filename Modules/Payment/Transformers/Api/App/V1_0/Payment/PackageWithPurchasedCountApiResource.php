<?php

namespace Modules\Payment\Transformers\Api\App\V1_0\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\Transformers\Api\App\V1_0\CoreKey\CoreKeyApiResource;

class PackageWithPurchasedCountApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $selectedIds = explode(',', $this->core_keys_id);

        $objs  = PaymentAttribute::whereIn("core_keys_id", $selectedIds)->get();

        $attributes = $objs->map(function ($key, $value) {
            if ($key['attribute_key'] == "currency_id") {
                $currency  = AvailableCurrency::where("id", $key['attribute_value'])->first();
                if ($currency) {
                    return [
                        'currency_symbol' => $currency->currency_symbol,
                        'currency_short_form' => $currency->currency_short_form,
                    ];
                }
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
        } else {
            if (!isset($data->type)) {
                $data->type = "";
            }
            if (!isset($data->price)) {
                $data->price = "";
            }
            if (!isset($data->count)) {
                $data->count = "";
            }
            if (!isset($data->status)) {
                $data->status = "";
            }
            if (!isset($data->currency_symbol)) {
                $data->currency_symbol = "";
            }
            if (!isset($data->currency_short_form)) {
                $data->currency_short_form = "";
            }
        }

        return [
            'id' => isset($this->id) ? (string)$this->id : '',
            'payment_id' => isset($this->payment_id) ? (string)$this->payment_id : '',
            'core_keys_id' => isset($this->core_keys_id) ? (string)$this->core_keys_id : '',
            'shop_id' => isset($this->shop_id) ? (string)$this->shop_id : '',
            "payment" => new PaymentApiResource($this->whenLoaded('payment')),
            "purchased_count" => isset($this->purchased_count) ? (string)$this->purchased_count->count() : '',
            "payment_info" => new PaymentInfoApiResource($this->whenLoaded('payment_info')),
            "payment_attributes" => count($data) > 0 ? $data : $empty,
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "is_empty_object" => $this->when(!isset($this->id), 1),
        ];
    }
}
