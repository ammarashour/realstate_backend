<?php

namespace Modules\Payment\Transformers\Backend\NoModel\OfflinePaymentSetting;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;

class OfflinePaymentSettingWithKeyResource extends JsonResource
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
            'title' => $this->core_key->name,
            'core_keys_id' => $this->core_keys_id,
            'description' => $this->value,
            'status' => $this->getStatus(),
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user@@name' => $this->getUpdatedUserName(),
            'updated_flag' => $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::offlinePaymentSettingModule, Auth::id())
        ];
    }

    private function getAddedUserName()
    {
        if(empty($this->owner)) {
            return '';
        }
        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {
        if(empty($this->editor)) {
            return '';
        }
        return $this->editor->name;
    }

    private function getStatus()
    {
        if(empty($this->statusAttribute)) {
            return 0;
        }
        return $this->statusAttribute->attribute_value;
    }
}
