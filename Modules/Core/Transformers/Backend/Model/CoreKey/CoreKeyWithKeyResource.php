<?php

namespace Modules\Core\Transformers\Backend\Model\CoreKey;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreKeyWithKeyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'core_keys_id' => $this->core_keys_id,
            'name' => $this->name,
            'description' => $this->description,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user_id@@name' => isset($this->owner) && !empty($this->owner) ?$this->owner->name:'',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user_id@@name' => isset($this->editor) && !empty($this->editor) ?$this->editor->name:'',
            'updated_flag' => $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
