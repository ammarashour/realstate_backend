<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Localization;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Localization\MobileLanguage;

class MobileLanguageStringApiResource extends JsonResource
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
            'mobile_langauge_id' => checkAndGetValue($this, 'mobile_langauge_id'),
            'key' => checkAndGetValue($this, 'key'),
            'value' => checkAndGetValue($this, 'value'),
            'status' => checkAndGetValue($this, 'status'),
            "mobile_language" => new MobileLanguage(checkAndGetValue($this, 'mobileLanguage') && $this->mobileLanguage ? $this->whenLoaded('mobile_language') : []),
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }
}
