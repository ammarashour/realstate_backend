<?php

namespace Modules\Payment\Transformers\Backend\Model\PaymentInfo;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Backend\Model\CoreKey\CoreKeyWithKeyResource;

class PaymentInfoWithKeyResource extends JsonResource
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
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'core_keys_id' => $this->core_keys_id,
            'core_key' => new CoreKeyWithKeyResource($this->whenLoaded('core_key')),
            'value' => $this->value,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => $this->updated_flag,
            'authorizations' => $this->authorization
        ];
    }
}
