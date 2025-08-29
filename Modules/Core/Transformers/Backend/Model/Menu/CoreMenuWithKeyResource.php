<?php

namespace Modules\Core\Transformers\Backend\Model\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreMenuWithKeyResource extends JsonResource
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
            'module_name' => (string) $this->module_name,
            'module_desc' => (string) $this->module_desc,
            'module_lang_key' => (string) $this->module_lang_key,
            'module_icon' => (string) $this->module_icon,
            'ordering' => (string) $this->ordering,
            'is_show_on_menu' => (string) $this->is_show_on_menu,
            'core_sub_menu_group_id' => (string) $this->core_sub_menu_group_id,
            'core_sub_menu_group@@name' => $this->getSubMenuGroupName(),
            "core_sub_menu_group_id@@sub_menu_desc" => $this->getSubMenuGroupDesc(),
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
        ];
    }

    private function getSubMenuGroupName()
    {
        if (empty($this->core_sub_menu_group)) {
            return '';
        }

        return $this->core_sub_menu_group->sub_menu_group_name;
    }

    private function getSubMenuGroupDesc()
    {
        if (empty($this->core_sub_menu_group)) {
            return '';
        }

        return $this->core_sub_menu_group->sub_menu_desc;
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
