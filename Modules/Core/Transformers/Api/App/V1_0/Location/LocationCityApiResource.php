<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationCityApiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => checkAndGetValue($this, 'id'),
            'name' => checkAndGetValue($this, 'name'),
            'lat' => checkAndGetValue($this, 'lat'),
            'lng' => checkAndGetValue($this, 'lng'),
            'ordering' => checkAndGetValue($this, 'ordering'),
            'status' => checkAndGetValue($this, 'status'),
            'description' => checkAndGetValue($this, 'description'),
            'touch_count' => checkAndGetValue($this, 'touch_count'),
            'is_featured' => checkAndGetValue($this, 'is_featured'),
            'featured_date' => checkAndGetValue($this, 'featured_date'),
            'cityRelation' => LocationCityInfoApiResource::collection($this->cityRelation ?? ['xxx']),
            'added_date_str' => $this->getAddedDateStr(),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }
}
