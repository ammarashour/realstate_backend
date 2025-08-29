<?php

namespace Modules\Core\Transformers\Backend\Model\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorWithKeyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'color' => (string)'bg-[#eee]',
            // 'color' => 'bg-['.$this->value.']', // bg-[#121212]
            'value' => $this->value,
            'title' => $this->title,
            'is_light_color' => $this->is_light_color,
            'is_common_color' => $this->is_common_color,
            'is_dark_color' => $this->is_dark_color,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
