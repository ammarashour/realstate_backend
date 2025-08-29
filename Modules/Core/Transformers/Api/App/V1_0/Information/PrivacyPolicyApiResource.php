<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Information;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivacyPolicyApiResource extends JsonResource
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
            'content' => checkAndGetValue($this, 'content'),
        ];
    }
}
