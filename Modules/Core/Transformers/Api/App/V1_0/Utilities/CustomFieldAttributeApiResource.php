<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Utilities;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomFieldAttributeApiResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => isset($this->id)?(string)$this->id:'',
            'name' => isset($this->name)?(string)$this->name:'',
            'core_keys_id' => isset($this->core_keys_id)?(string)$this->core_keys_id:'',
            'added_date' => isset($this->added_date)?(string)$this->added_date:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
