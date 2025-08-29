<?php

namespace Modules\Core\Transformers\Backend\Model\ThemeScreen;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemePlatformWithKeyResource extends JsonResource
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
            'name' => $this->name,
            'added_date' => $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'updated_date' => $this->updated_date,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_flag' => $this->updated_flag,
        ];
    }
}
