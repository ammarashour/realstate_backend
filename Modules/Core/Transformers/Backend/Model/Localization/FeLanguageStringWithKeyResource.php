<?php

namespace Modules\Core\Transformers\Backend\Model\Localization;

use Illuminate\Http\Resources\Json\JsonResource;

class FeLanguageStringWithKeyResource extends JsonResource
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
            'langauge_id' => (string) $this->langauge_id,
            "language@@symbol" => (string) $this->language->symbol,
            "language_id@@name" => (string) $this->language->name,
            'key' => (string) $this->key,
            'value' => (string) $this->value,
            'status' => (string) $this->status,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
        ];
    }

    private function getAddedUserName()
    {
        if(empty($this->owner))
        {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {
        if(empty($this->editor))
        {
            return '';
        }

        return $this->editor->name;
    }
}

