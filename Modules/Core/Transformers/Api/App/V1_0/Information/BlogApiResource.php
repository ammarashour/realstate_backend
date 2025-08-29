<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Information;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationCityApiResource;

class BlogApiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => checkAndGetValue($this, 'id'),
            'name' => checkAndGetValue($this, 'name'),
            'description' => checkAndGetValue($this, 'description'),
            'location_city_id' => checkAndGetValue($this, 'location_city_id'),
            'shop_id' => checkAndGetValue($this, 'shop_id'),
            'status' => checkAndGetValue($this, 'status'),
            'city' => new LocationCityApiResource($this->city),
            'default_photo' => new CoreImageApiResource($this->cover ?? []),
            'added_date_str' => $this->getAddedDateStr(),
            'added_user_name' => $this->getAddedUserName(),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedUserName()
    {

        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }
}
