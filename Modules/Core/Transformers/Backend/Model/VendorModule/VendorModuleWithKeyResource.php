<?php

namespace Modules\Core\Transformers\Backend\Model\VendorModule;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorModuleWithKeyResource extends JsonResource
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
            'module_id' => (string) $this->id,
            'title' => (string) $this->title,
            'lang_key' => (string) $this->lang_key,
            'is_not_from_sidebar' => (string) $this->is_not_from_sidebar,
            'status' => (string) $this->status,
            'route_name' => (string) $this->route_name,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor)? $this->editor->name:'',
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
        ];
    }
}
