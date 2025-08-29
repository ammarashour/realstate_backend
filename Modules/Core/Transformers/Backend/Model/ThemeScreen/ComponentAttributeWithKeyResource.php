<?php

namespace Modules\Core\Transformers\Backend\Model\ThemeScreen;

use Illuminate\Http\Resources\Json\JsonResource;

class ComponentAttributeWithKeyResource extends JsonResource
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
            'name' => (string) $this->name,
            'component_id' => $this->component_id,
            'platform_id' => $this->platform_id,
            'screen_id' => $this->screen_id,
            'theme_id' => $this->theme_id,
            'attributes' => json_decode($this->attributes),
            'added_date' => $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
        ];
    }
}
