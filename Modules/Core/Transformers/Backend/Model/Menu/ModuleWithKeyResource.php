<?php

namespace Modules\Core\Transformers\Backend\Model\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleWithKeyResource extends JsonResource
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
            'title' => (string) $this->title,
            'status' => (string) $this->status,
            'route_name' => (string) $this->route_name,
            'lang_key' => (string) $this->lang_key,
            'is_not_from_sidebar' => (string) $this->is_not_from_sidebar,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedUserName()
    {

        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {
        if (empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }
}
