<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class SlowMovingItemApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
