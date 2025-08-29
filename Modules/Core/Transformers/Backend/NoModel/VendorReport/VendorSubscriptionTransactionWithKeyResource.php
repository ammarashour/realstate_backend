<?php

namespace Modules\Core\Transformers\Backend\NoModel\VendorReport;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Carbon\Carbon;
use App\Models\User;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Entities\PaymentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Financial\PackageInAppPurchaseSettingApiResource;

class VendorSubscriptionTransactionWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        //for expired date
        $duration = 0;
        $paymentAttributes = PaymentAttribute::where([PaymentAttribute::coreKeysId => $this->core_keys_id,PaymentAttribute::attributeKey => "duration"])->get();
        if(isset($paymentAttributes)){
            foreach($paymentAttributes as $attribute){
                $duration = $attribute->attribute_value;
            }
        }
        $expiredDate = $this->expired_date == null ? $this->added_date->addMonths($duration) : Carbon::parse($this->expired_date);
        return [
            'id' => (string)$this->id,
            'user_id' => (string)$this->user_id,
            'package_id' => (string)$this->package_id,
            'payment_method' => (string)$this->payment_method,
            'price' => (string)$this->price,
            'razor_id' => (string)$this->razor_id,
            'isPaystack' => (string)$this->is_paystack,
            // 'status' => isset($this->status)?(string)$this->status:'',
            'status' => $expiredDate->gt(Carbon::now())?1:0,
            'transaction_id' => (string)$this->transaction_id,
            'added_date' => (string)$this->added_date,
            'expired_date' => (string)$expiredDate,
            "user" => new UserApiResource(isset($this->user) && $this->user ? $this->whenLoaded('user'): []),
            "package" => new PackageInAppPurchaseSettingApiResource($this->whenLoaded('package') instanceof PaymentInfo ? $this->whenLoaded('package'): new PaymentInfo()  
            ),
            "added_date_str" => (string)$this->added_date->diffForHumans(),
            "is_empty_object" => $this->when(!isset($this->id),1),
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::packageReportModule, Auth::id())
        ];
    }
}
