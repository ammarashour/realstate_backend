<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class CategoryApiResource extends JsonResource
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
            'name' => $this->getCategoryName(),
            'ordering' => checkAndGetValue($this, 'ordering'),
            'status' => checkAndGetValue($this, 'status'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'default_photo' => new CoreImageApiResource($this->cover ?? []),
            'default_icon' => new CoreImageApiResource($this->icon ?? []),
            'added_date_str' => $this->getAddedDateStr(),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
            'category_touch_count' => (string)$this->getTouchCount(),
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

    private function getTouchCount()
    {
        if (empty($this->category_touch)) {
            return null;
        }

        return $this->category_touch->count();
    }

    private function getCategoryName()
    {
        if (empty($this->categoryLanguageString)) {
            return $this->name ?? '';
        }

        return $this->categoryLanguageString?->value;
    }
}
