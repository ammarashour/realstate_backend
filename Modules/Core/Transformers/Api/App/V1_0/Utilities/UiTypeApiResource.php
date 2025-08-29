<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Utilities;

use Illuminate\Http\Resources\Json\JsonResource;

class UiTypeApiResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (string)checkAndGetValue($this, 'id'),
            'name' => (string)checkAndGetValue($this, 'name'),
            'core_keys_id' => (string)checkAndGetValue($this, 'core_keys_id'),
            'added_date' => (string)checkAndGetValue($this, 'added_date'),
            "added_date_str" => (string)$this->getAddedDateStr(),
            "is_empty_object" => $this->when(!isset($this->id),1),
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
