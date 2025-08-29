<?php

namespace Modules\Payment\Transformers\Backend\NoModel\PaymentSetting;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Transformers\Backend\Model\PaymentInfo\PaymentInfoWithKeyResource;

class PaymentSettingWithKeyResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'payment_infos' => PaymentInfoWithKeyResource::collection($this->whenLoaded('payment_infos')),
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name : '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::paymentSettingModule, Auth::id())
        ];
    }
}
