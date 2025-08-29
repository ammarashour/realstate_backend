<?php

namespace Modules\Core\Transformers\Backend\Model\Table;

use Illuminate\Http\Resources\Json\JsonResource;

class TableWithKeyResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => (string)$this->id,
            'name' => (string)$this->name,
            'description' => (string)$this->description,
            'core_key_type_id' => (string)$this->core_key_type_id,
            'is_only_for_core_field' => (string)$this->is_only_for_core_field,
            'table_used_type_id' => (string)$this->table_used_type_id,
            'added_date' => (string)$this->added_date,
            'added_user_id' => (string)$this->added_user_id,
            'updated_date' => (string)$this->updated_date,
            'updated_user_id' => (string)$this->updated_user_id,
            'updated_flag' => (string)$this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
