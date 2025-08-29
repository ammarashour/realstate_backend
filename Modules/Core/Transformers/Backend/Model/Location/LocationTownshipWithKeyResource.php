<?php

namespace Modules\Core\Transformers\Backend\Model\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationTownshipWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->location_city);
        return [
            'id' => (string) $this->id,
            'name' => (string) $this->name,
            'location_city_id' => (string) $this->location_city_id,
            'location_city_id@@name' => $this->getLocationCityName(),
            'lat' => (string) $this->lat,
            'lng' => (string) $this->lng,
            'ordering' => (string) $this->ordering,
            'status' => (string) $this->status,
            'description' => (string) $this->description,
            'touch_count' => (string) $this->touch_count,
            'is_featured' => (string) $this->is_featured,
            'featured_date' => (string) $this->featured_date,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user_id@@name' => $this->getAddedUserName(),
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user_id@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }

    private function getLocationCityName()
    {
        if(empty($this->location_city)) {
            return '';
        }

        return $this->location_city->name;
    }

    private function getAddedUserName()
    {
        if(empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {
        if(empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }
}
