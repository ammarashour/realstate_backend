<?php

namespace Modules\Core\Transformers\Backend\Model\Localization;

use Illuminate\Http\Resources\Json\JsonResource;

class MobileLanguageStringWithKeyResource extends JsonResource
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
            'id' => (string) $this->id,
            'mobile_langauge_id' => (string) $this->mobile_langauge_id,
            "mobile_language@@symbol" => (string) $this->mobileLanguage->symbol,
            'key' => (string) $this->key,
            'value' => (string) $this->value,
            'status' => (string) $this->status,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor)? $this->editor->name:'',
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
