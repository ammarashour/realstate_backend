<?php

namespace Modules\Core\Transformers\Backend\Model\Localization;

use Illuminate\Http\Resources\Json\JsonResource;

class MobileLanguageWithKeyResource extends JsonResource
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
            'symbol' => (string) $this->symbol,
            'language_code' => (string) $this->language_code,
            'country_code' => (string) $this->country_code,
            'name' => (string) $this->name,
            'code' => (string) $this->code,
            'status' => (string) $this->status,
            'enable' => (string) $this->enable,
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
