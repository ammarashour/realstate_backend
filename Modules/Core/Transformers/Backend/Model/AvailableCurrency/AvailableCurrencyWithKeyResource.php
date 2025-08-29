<?php

namespace Modules\Core\Transformers\Backend\Model\AvailableCurrency;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableCurrencyWithKeyResource extends JsonResource
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
            'currency_short_form' => (string)$this->currency_short_form,
            'currency_symbol' => (string)$this->currency_symbol,
            'name' => (string)$this->name,
            'status' => (string)$this->status,
            'is_default' => (string)$this->is_default,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor)? $this->editor->name:'',
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
