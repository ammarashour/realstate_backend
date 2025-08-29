<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use stdClass;

class BillingForUserApiResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            "id" => (string)$this->id,
            "billing_first_name" => (string)$this->first_name,
            "billing_last_name" => (string)$this->last_name,
            "billing_email" => (string)$this->email,
            "billing_phone_no" => (string)$this->phone_no,
            "billing_address" => (string)$this->address,
            "billing_country" => (string)$this->country,
            "billing_state" => (string)$this->state,
            "billing_city" => (string)$this->city,
            "billing_postal_code" => (string)$this->postal_code,
            "is_save_shipping_info_for_next_time" => (string)$this->is_save_shipping_info_for_next_time,
            "is_save_billing_info_for_next_time" => (string)$this->is_save_billing_info_for_next_time,
            // 'added_date' => isset($this->added_date)?(string)$this->added_date:'',
        ];
    }
}
