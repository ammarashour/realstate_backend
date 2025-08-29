<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Support;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsMessageApiResource extends JsonResource
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
            'id' => isset($this->id)?(string)$this->id:'',
            'contact_name' => isset($this->contact_name)?(string)$this->contact_name:'',
            'contact_email' => isset($this->contact_email)?(string)$this->contact_email:'',
            'contact_phone' => isset($this->contact_phone)?(string)$this->contact_phone:'',
            'contact_message' => isset($this->contact_message)?(string)$this->contact_message:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
