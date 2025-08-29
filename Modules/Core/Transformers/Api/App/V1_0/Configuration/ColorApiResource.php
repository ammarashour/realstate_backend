<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Configuration\Color;

class ColorApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $colors = Color::where('mb_color',1)->get();

        $mobile_color = $colors->map(function ($color){
            return[
                $color->key => (string)$color->value,
            ];
        })->collapse();

        return $mobile_color;
    }
}

