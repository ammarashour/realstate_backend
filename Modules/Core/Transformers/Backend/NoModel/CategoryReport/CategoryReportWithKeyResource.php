<?php

namespace Modules\Core\Transformers\Backend\NoModel\CategoryReport;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;

class CategoryReportWithKeyResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'name' => (string)__($this->name),
            'category_touch_count' => (string)$this->category_touch_count,
            'ordering' => (string)$this->ordering,
            // 'updated_user_id@@name' => (string)$this->editor->name,
            'status' => (string)$this->status,
            'added_date' => (string)$this->added_date,
            'added_user_id' => (string)$this->added_user_id,
            'added_user_id@@name' => isset($this->owner) && $this->owner ? (string)$this->owner->name : '',
            'updated_date' => (string)$this->updated_date,
            'updated_user_id' => (string)$this->updated_user_id,
            'updated_flag' => (string)$this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::categoryReportModule, Auth::id())
        ];
    }
}
