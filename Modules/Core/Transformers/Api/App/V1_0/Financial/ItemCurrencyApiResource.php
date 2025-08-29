<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemCurrencyApiResource extends JsonResource
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
            'currency_short_form' => checkAndGetValue($this, 'currency_short_form'),
            'currency_symbol' => checkAndGetValue($this, 'currency_symbol'),
            'status' => checkAndGetValue($this, 'status'),
            'is_default' => checkAndGetValue($this, 'is_default'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            "added_date_str" => $this->getAddedDateStr(),
            "is_empty_object" => checkAndGetValue($this, 'id',1), 
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

