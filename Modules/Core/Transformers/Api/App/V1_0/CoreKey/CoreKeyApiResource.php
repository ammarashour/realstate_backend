<?php

namespace Modules\Core\Transformers\Api\App\V1_0\CoreKey;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreKeyApiResource extends JsonResource
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
            'core_keys_id' => isset($this->core_keys_id)?(string)$this->core_keys_id:'',
            'name' => isset($this->name)?(string)$this->name:'',
            'description' => isset($this->description)?(string)$this->description:'',
            'status' => isset($this->status)?(string)$this->status:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
