<?php

namespace Modules\Payment\Transformers\Api\App\V1_0\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\CoreKeyApiResource;


class OfflinePaymentSettingApiResource extends JsonResource
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
            'shop_id' => checkAndGetValue($this, 'shop_id'),
            'title' => $this->getTitle(),
            'description' => checkAndGetValue($this, 'value'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_user_id' => checkAndGetValue($this, 'added_user_id'),
            'updated_date' => checkAndGetValue($this, 'updated_date'),
            'updated_user_id' => checkAndGetValue($this, 'updated_user_id'),
            "default_icon" => new CoreImageApiResource($this->offline_icon ?? []),
            "added_date_str" => $this->getAddedDateStr(),
            "is_empty_object" => checkAndGetValue($this, 'id', 1),
        ];
    }

    private function getTitle()
    {
        if(empty($this->core_key)){
            return '';
        }
        return $this->core_key->name;
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
