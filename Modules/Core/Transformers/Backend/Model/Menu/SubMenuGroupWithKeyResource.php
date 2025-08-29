<?php

namespace Modules\Core\Transformers\Backend\Model\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class SubMenuGroupWithKeyResource extends JsonResource
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
            'sub_menu_name' => (string) $this->sub_menu_name,
            'sub_menu_desc' => (string) $this->sub_menu_desc,
            'sub_menu_lang_key' => (string) $this->sub_menu_lang_key,
            'sub_menu_icon' => (string) $this->sub_menu_icon,
            'ordering' => (string) $this->ordering,
            'ordering' => (string) $this->ordering,
            'is_show_on_menu' => (string) $this->is_show_on_menu,
            'core_menu_group_id' => (string) $this->core_menu_group_id,
            'core_menu_group@@group_name' => $this->getCoreMenuGroupName(),
            'core_menu_group_id@@group_name' => $this->getCoreMenuGroupName(),
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

    private function getCoreMenuGroupName()
    {

        if (empty($this->core_menu_group)) {
            return '';
        }

        return $this->core_menu_group->group_name;
    }

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
