<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\SubcatSubscribe;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class SubcategoryApiResource extends JsonResource
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
            'id' => checkAndGetValue($this, 'id'),
            'name' => $this->getSubCategoryName(),
            'category_id' => checkAndGetValue($this, 'category_id'),
            'ordering' => checkAndGetValue($this, 'ordering'),
            'status' => checkAndGetValue($this, 'status'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_user_id' => checkAndGetValue($this, 'added_user_id'),
            'updated_date' => checkAndGetValue($this, 'updated_date'),
            'updated_user_id' => checkAndGetValue($this, 'updated_user_id'),
            'updated_flag' => checkAndGetValue($this, 'updated_flag'),
            'is_subscribed_mb' => $this->isSubscribedFrom('MB'),
            'is_subscribed_fe' => $this->isSubscribedFrom('FE'),
            'added_date_str' => $this->getAddedDateStr(),
            'default_photo' => new CoreImageApiResource($this->cover ?? []),
            'default_icon' => new CoreImageApiResource($this->icon ?? []),
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }

    private function isSubscribedFrom($platform)
    {
        $conds[SubcatSubscribe::user_id] = $_GET['login_user_id'] ?? Auth::id();
        $conds[SubcatSubscribe::subcat_id] = checkAndGetValue($this, 'id').'_'.$platform;

        $subcat_fe = SubcatSubscribe::where($conds)->count();

        if ($subcat_fe == '1') {
            return '1';
        }

        return '0';
    }

    private function getSubCategoryName()
    {
        if (empty($this->subCategoryLanguageString)) {
            return $this->name ?? '';
        }

        return $this->subCategoryLanguageString?->value;
    }
}
