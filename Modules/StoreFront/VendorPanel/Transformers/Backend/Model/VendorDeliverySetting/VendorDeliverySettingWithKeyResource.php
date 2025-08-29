<?php

namespace Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorDeliverySetting;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorDeliverySettingWithKeyResource extends JsonResource
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
            'id' => isset($this->id)?(string) $this->id:'',
            'vendor_id' => isset($this->id)?(string) $this->vendor_id:'',
            'delivery_setting' => isset($this->id)?(string) $this->delivery_setting:'',
            'delivery_charges' => isset($this->id)?(string) $this->delivery_charges:'',
            'added_date' => isset($this->id)?(string) $this->added_date:'',
            'added_user_id' => isset($this->id)?(string) $this->added_user_id:'',
            'added_user@@name' => isset($this->id)?(string) $this->owner->name:'',
            'updated_user_id' => isset($this->id)?(string) $this->updated_user_id:'',
            'updated_user@@name' => !empty($this->editor)? $this->editor->name:'',
            'updated_flag' => isset($this->id)?(string) $this->updated_flag:'',
            'authorization' => $this->vendorAuthorization
        ];
    }
}
