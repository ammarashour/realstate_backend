<?php

namespace Modules\Core\Transformers\Api\App\V1_0\CoreImage;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreImageApiResource extends JsonResource
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
            'img_id' => isset($this->id)?(string)$this->id:'',
            'img_parent_id' => isset($this->img_parent_id)?(string)$this->img_parent_id:'',
            'img_type' => isset($this->img_type)?(string)$this->img_type:'',
            'img_path' => isset($this->img_path)?(string)$this->img_path:'',
            'img_width' => isset($this->img_width)?(string)$this->img_width:'',
            'img_height' => isset($this->img_height)?(string)$this->img_height:'',
            'img_desc' => isset($this->img_desc)?(string)$this->img_desc:'',
            'ordering' => isset($this->ordering)?(string)$this->ordering:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
