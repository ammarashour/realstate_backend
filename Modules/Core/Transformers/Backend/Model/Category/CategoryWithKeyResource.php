<?php

namespace Modules\Core\Transformers\Backend\Model\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryWithKeyResource extends JsonResource
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
            'name' => (string) $this->name,
            'ordering' => (string) $this->ordering,
            'updated_user_id@@name' => $this->getUpdatedUserName(),
            'status' => (string) $this->status,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user_id@@name' => $this->getAddedUserName(),
            'updated_date' => (string) $this->updated_date,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization,
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
