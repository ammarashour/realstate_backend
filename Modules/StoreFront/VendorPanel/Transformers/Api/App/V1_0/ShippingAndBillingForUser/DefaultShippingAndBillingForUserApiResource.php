<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use stdClass;

class DefaultShippingAndBillingForUserApiResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "shipping_id" => (string)$this->defaultShippingInfo->id,
            "shipping_first_name" => (string)$this->defaultShippingInfo->first_name,
            "shipping_last_name" => (string)$this->defaultShippingInfo->last_name,
            "shipping_email" => (string)$this->defaultShippingInfo->email,
            "shipping_phone_no" => (string)$this->defaultShippingInfo->phone_no,
            "shipping_address" => (string)$this->defaultShippingInfo->address,
            "shipping_country" => (string)$this->defaultShippingInfo->country,
            "shipping_state" => (string)$this->defaultShippingInfo->state,
            "shipping_city" => (string)$this->defaultShippingInfo->city,
            "shipping_postal_code" => (string)$this->defaultShippingInfo->postal_code,
            "is_save_shipping_info_for_next_time" => (string)$this->defaultShippingInfo->is_save_shipping_info_for_next_time,
            "billing_id" => (string)$this->defaultBillingInfo->id,
            "billing_first_name" => (string)$this->defaultBillingInfo->first_name,
            "billing_last_name" => (string)$this->defaultBillingInfo->last_name,
            "billing_email" => (string)$this->defaultBillingInfo->email,
            "billing_phone_no" => (string)$this->defaultBillingInfo->phone_no,
            "billing_address" => (string)$this->defaultBillingInfo->address,
            "billing_country" => (string)$this->defaultBillingInfo->country,
            "billing_state" => (string)$this->defaultBillingInfo->state,
            "billing_city" => (string)$this->defaultBillingInfo->city,
            "billing_postal_code" => (string)$this->defaultBillingInfo->postal_code,
            "is_save_billing_info_for_next_time" => (string)$this->defaultBillingInfo->is_save_billing_info_for_next_time,
            // 'added_date' => isset($this->added_date)?(string)$this->added_date:'',
        ];
    }
}
