<?php

namespace Modules\Core\Transformers\Backend\Model\Information;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogWithKeyResource extends JsonResource
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
            'description' => (string) $this->description,
            'location_city_id' => $this->location_city_id,
            'location_city_id@@name' => $this->getLocationCity(),
            'status' => (string) $this->status,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization,
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getLocationCity()
    {

        if (empty($this->city)) {
            return 'All';
        }

        return $this->city->name;
    }

    private function getUpdatedUserName()
    {
        if (empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }
}
