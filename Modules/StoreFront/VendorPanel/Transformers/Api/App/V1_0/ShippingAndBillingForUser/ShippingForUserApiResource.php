<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use stdClass;

class ShippingForUserApiResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            "id" => (string)$this->id,
            "shipping_first_name" => (string)$this->first_name,
            "shipping_last_name" => (string)$this->last_name,
            "shipping_email" => (string)$this->email,
            "shipping_phone_no" => (string)$this->phone_no,
            "shipping_address" => (string)$this->address,
            "shipping_country" => (string)$this->country,
            "shipping_state" => (string)$this->state,
            "shipping_city" => (string)$this->city,
            "shipping_postal_code" => (string)$this->postal_code,
            "is_save_shipping_info_for_next_time" => (string)$this->is_save_shipping_info_for_next_time,
            "is_save_billing_info_for_next_time" => (string)$this->is_save_billing_info_for_next_time,
            // 'added_date' => isset($this->added_date)?(string)$this->added_date:'',
        ];
    }
}
