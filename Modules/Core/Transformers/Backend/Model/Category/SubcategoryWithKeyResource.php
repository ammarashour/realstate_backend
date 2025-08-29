<?php

namespace Modules\Core\Transformers\Backend\Model\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryWithKeyResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => (string) $this->id,
            'name' => (string) $this->name,
            'category_id' => (string) $this->category_id,
            'category_id@@name' => $this->getCatgoryName(),
            'ordering' => (string) $this->ordering,
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

    private function getCatgoryName()
    {

        if (empty($this->category->categoryLanguageString)) {
            return $this->category->name ?? '';
        }

        return $this->category->categoryLanguageString->value;
    }

    private function getAddedUserName()
    {
        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }
}
