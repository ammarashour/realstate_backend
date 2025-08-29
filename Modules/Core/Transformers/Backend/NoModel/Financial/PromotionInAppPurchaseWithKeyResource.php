<?php

namespace Modules\Core\Transformers\Backend\NoModel\Financial;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;

class PromotionInAppPurchaseWithKeyResource extends JsonResource
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
            'id' => $this->id,
            'in_app_purchase_prd_id' => !empty($this->core_key) ? $this->core_key->name:'',
            'core_keys_id' => $this->core_keys_id,
            'description' => $this->value,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'days' => $this->days,
            'type' => $this->type,
            'status' => $this->status,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::promotionInAppPurchaseModule, Auth::id())
        ];
    }
}
