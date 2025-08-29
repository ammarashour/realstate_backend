<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Vendor;

use Carbon\Carbon;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Constants\Constants;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorInfoApiResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorApplicationApiResource;

class VendorApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        $product_count = 0;
        if (isset($this->id)) {
            $product_count = productCountByVendorId($this->id);
        }

        $vendor_subcription_setting = Setting::select("setting")->where("setting_env", Constants::VENDOR_SUBSCRIPTION_CONFIG)->first();
        $jsonSetting = json_decode($vendor_subcription_setting->setting, true);
        $idValue = $jsonSetting['subscription_plan'][0]['id'];
        $noticDays = $jsonSetting['notic_days'];

        // for vendor expery status
        $expireStatus = 0;
        if ($idValue != 'FREE') {
            if (isset($this->expired_date)) {
                $currentDate = Carbon::now();
                $notiDate = Carbon::parse($this->expired_date)->subDays((int)$noticDays - 1);
                $expiredDate = Carbon::parse($this->expired_date);

                if ($currentDate->lt($notiDate)) {
                    $expireStatus = 0; //not show noti
                }
                if (($currentDate->gt($notiDate) || $currentDate->eq($notiDate)) && $currentDate->lt($expiredDate)) {
                    $expireStatus = 1; //show warning noti
                }
                if ($currentDate->gt(Carbon::parse($this->expired_date)->subDays(1))) {
                    $expireStatus = 2; //show expired noti
                }
                if ($this->is_unlimited) {
                    $expireStatus = 0;
                }
            }
        }
        return [
            'id' => checkAndGetValue($this, 'id'),
            'owner_user_id' => checkAndGetValue($this, 'owner_user_id'),
            'status' => checkAndGetValue($this, 'status'),
            'name' => checkAndGetValue($this, 'name'),
            "phone" => checkAndGetValue($this, 'phone'),
            "email" => checkAndGetValue($this, 'email'),
            'address' => checkAndGetValue($this, 'address'),
            'description' => checkAndGetValue($this, 'description'),
            'website' => checkAndGetValue($this, 'website'),
            'facebook' => checkAndGetValue($this, 'facebook'),
            'instagram' => checkAndGetValue($this, 'instagram'),
            'product_count' => (string)$product_count,
            'currency_id' => checkAndGetValue($this, 'currency_id'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'expired_date' => checkAndGetValue($this, 'expired_date'),
            'expire_status' => $expireStatus,
            "logo" => new CoreImageApiResource(checkAndGetValue($this, 'logo') && $this->logo ? $this->whenLoaded('logo') : []),
            "banner_1" => new CoreImageApiResource(checkAndGetValue($this, 'banner_1') && $this->banner_1 ? $this->whenLoaded('banner_1') : []),
            "banner_2" => new CoreImageApiResource(checkAndGetValue($this, 'banner_2') && $this->banner_2 ? $this->whenLoaded('banner_2') : []),
            "vendor_application" => new VendorApplicationApiResource(checkAndGetValue($this, 'owner_user_id') && checkAndGetValue($this, 'vendor_application') ? $this->whenLoaded('vendor_application') : []),
            "added_date_str" => checkAndGetValue($this, 'added_date') ? (string)$this->added_date->diffForHumans() : '',
            'is_empty_object' => checkAndGetValue($this, 'id', 1),
            'updated_flag' => checkAndGetValue($this, 'updated_flag'),
            "vendorRelation" => VendorInfoApiResource::collection($this->vendorInfo ?? []),
        ];
    }
}
