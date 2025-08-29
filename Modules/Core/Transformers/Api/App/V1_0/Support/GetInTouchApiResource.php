<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Support;

use Illuminate\Http\Resources\Json\JsonResource;

class GetInTouchApiResource extends JsonResource
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
            'about_email' => isset($this->about_email)?(string)$this->about_email:'',
            'about_phone' => isset($this->about_phone)?(string)$this->about_phone:'',
            'about_address' => isset($this->about_address)?(string)$this->about_address:'',
        ];
    }
}
