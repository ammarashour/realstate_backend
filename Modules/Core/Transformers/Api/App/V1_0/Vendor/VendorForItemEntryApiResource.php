<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class VendorForItemEntryApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string)checkAndGetValue($this, 'id'),
            'name' => (string)checkAndGetValue($this, 'name'),
            'currency_id' => (string)checkAndGetValue($this, 'currency_id'),
            'currency_symbol' => isset($this->vendorCurrency)?$this->vendorCurrency->currency_symbol:'',
            "logo" => new CoreImageApiResource($this->logo ?? []),
        ];
    }
}
