<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Utilities;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreFieldApiResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => checkAndGetValue($this, 'id'),
            'field_name' => (string) $this->getOriginalFieldName(),
            'label_name' => $this->getLabelName($request),
            'placeholder' => $this->getPlaceholder($request),
            'data_type' => checkAndGetValue($this, 'date_type'),
            'is_core_field' => checkAndGetValue($this, 'is_core_field'),
            'is_visible' => checkAndGetValue($this, 'enable'),
            'mandatory' => checkAndGetValue($this, 'mandatory'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            "added_date_str" => $this->getAddedDateStr(),
            "is_empty_object" => $this->when(!isset($this->id),1),

        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getLabelName($request)
    {
        return __(checkAndGetValue($this, 'label_name'), [], $request->language_symbol);
    }

    private function getPlaceholder($request)
    {
        return __(checkAndGetValue($this, 'placeholder'), [], $request->language_symbol);
    }

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }

    private function getOriginalFieldName()
    {
        if (str_contains($this->field_name,"@@")) {
            $originFieldName = strstr($this->field_name,"@@",true);
        } else {
            $originFieldName = $this->field_name;
        }

        return $originFieldName;
    }
}
