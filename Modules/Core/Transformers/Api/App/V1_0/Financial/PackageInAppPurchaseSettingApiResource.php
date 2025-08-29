<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Financial;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;
use Modules\Core\Transformers\Api\App\V1_0\CoreKey\CoreKeyApiResource;
use Modules\Payment\Transformers\Api\App\V1_0\Payment\PaymentApiResource;
use Modules\Payment\Transformers\Api\App\V1_0\Payment\PaymentInfoApiResource;

class PackageInAppPurchaseSettingApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $coreKeysId = $this->resource->core_keys_id ?? null;
        $selectedIds = explode(',', $coreKeysId);

        $objs  = PaymentAttribute::whereIn("core_keys_id", $selectedIds)->get();

        $attributes = $objs ->map(function ($key, $value){
            if ($key['attribute_key'] == "currency_id") {
                $currency  = AvailableCurrency::where("id", $key['attribute_value'])->first();
                if($currency){
                    return[
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

        if(count($data)==0){
            $empty = new \stdClass;
            $empty->type = "";
            $empty->count = "";
            $empty->price = "";
            $empty->status = "";
            $empty->currency_symbol = "";
            $empty->currency_short_form = "";
            $empty->is_empty_object = 1;
        }else{
            if(!isset($data->type)){
                $data->type = "";
            }
            if(!isset($data->price)){
                $data->price = "";
            }
            if(!isset($data->count)){
                $data->count = "";
            }
            if(!isset($data->status)){
                $data->status = "";
            }
            if(!isset($data->currency_symbol)){
                $data->currency_symbol = "";
            }
            if(!isset($data->currency_short_form)){
                $data->currency_short_form = "";
            }
        }

        return [
            'id' => checkAndGetValue($this, 'id'),
            'payment_id' => checkAndGetValue($this, 'payment_id'),
            'core_keys_id' => checkAndGetValue($this, 'core_keys_id'),
            'shop_id' => checkAndGetValue($this, 'shop_id'),
            "payment" => new PaymentApiResource($this->whenLoaded('payment')),
            "core_key" => new CoreKeyApiResource($this->whenLoaded('core_key')),
            "payment_info" => new PaymentInfoApiResource($this->whenLoaded('payment_info')),
            "payment_attributes" => count($data)>0?$data:$empty,
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            "is_empty_object" => checkAndGetValue($this, 'id', 1),
        ];
    }
}
