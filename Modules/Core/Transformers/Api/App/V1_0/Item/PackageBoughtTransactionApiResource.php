<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Transformers\Api\App\V1_0\Financial\PackageInAppPurchaseSettingApiResource;
use App\Config\ps_constant;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;

class PackageBoughtTransactionApiResource extends JsonResource
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
            'id' => isset($this->id)?(string)$this->id:'',
            'user_id' => isset($this->user_id)?(string)$this->user_id:'',
            'package_id' => isset($this->package_id)?(string)$this->package_id:'',
            'payment_method' => isset($this->payment_method)?(string)$this->payment_method:'',
            'price' => isset($this->price)?(string)$this->price:'',
            'razor_id' => isset($this->razor_id)?(string)$this->razor_id:'',
            'isPaystack' => isset($this->is_paystack)?(string)$this->is_paystack:'',
            'status' => isset($this->status)?(string)$this->status:'',
            'transaction_id' => isset($this->transaction_id)?(string)$this->transaction_id:'',
            'added_date' => isset($this->added_date)?(string)$this->added_date:'',
            "user" => new UserApiResource(isset($this->user) && $this->user ? $this->whenLoaded('user'): User::where('id', 0)->get()),
            "package" => new PackageInAppPurchaseSettingApiResource(isset($this->package) && $this->package ? $this->whenLoaded('package'): PaymentInfo::where('id', 0)->get()),
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::packageReportModule, Auth::id())
        ];
    }
}
