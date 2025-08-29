<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Localization;

use Illuminate\Http\Resources\Json\JsonResource;

class MobileLanguageApiResource extends JsonResource
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
            'id' => checkAndGetValue($this, 'id'),
            'symbol' => checkAndGetValue($this, 'symbol'),
            'language_code' => checkAndGetValue($this, 'language_code'),
            'country_code' => checkAndGetValue($this, 'country_code'),
            'name' => checkAndGetValue($this, 'name'),
            'code' => checkAndGetValue($this, 'code'),
            'status' => checkAndGetValue($this, 'status'),
            'enable' => checkAndGetValue($this, 'enable'),
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            // "is_empty_object" => checkAndGetValue($this, 'id', 1),
        ];
    }
}
