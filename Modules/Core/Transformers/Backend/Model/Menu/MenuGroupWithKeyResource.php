<?php

namespace Modules\Core\Transformers\Backend\Model\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuGroupWithKeyResource extends JsonResource
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
            'group_name' => (string) $this->group_name,
            'group_desc' => (string) $this->group_desc,
            'group_lang_key' => (string) $this->group_lang_key,
            'group_icon' => (string) $this->group_icon,
            'is_show_on_menu' => (string) $this->is_show_on_menu,
            'ordering' => (string) $this->ordering,
            'is_invisible_group_name' => (string) $this->is_invisible_group_name,
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
