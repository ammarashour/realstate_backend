<?php

namespace Modules\Core\Transformers\Backend\Model\Vendor;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Backend\Model\User\UserWithKeyResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class VendorWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        

        $changedCustomFieldFormat = [];
        $customizeDetails = CustomFieldAttribute::latest()->get();

        $customFields = $this->vendorInfo;
        foreach ($customFields as $customField) {
            if (!empty($customField->customizeUi)) {
                if ($customField->customizeUi->enable === 1 && $customField->customizeUi->is_delete === 0) {

                    $coreKeysId = $customField->core_keys_id;
                    $value = '';
                    if ($customField->ui_type_id === Constants::dropDownUi){
                        foreach ($customizeDetails as $customizeDetail){
                            if ($customizeDetail->id == $customField->value){
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id . "@@name";
                        $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;

                    } elseif ($customField->ui_type_id === Constants::radioUi){
                        foreach ($customizeDetails as $customizeDetail){
                            if ($customizeDetail->id == $customField->value){
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id . "@@name";
                        $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;

                    } elseif ($customField->ui_type_id === Constants::dateTimeUi){
                        $value = $customField->value;
                    } elseif ($customField->ui_type_id === Constants::textAreaUi){
                        $value = Str::words($customField->value, 5, '...');
                    } elseif ($customField->ui_type_id === Constants::timeOnlyUi){
                        $value = $customField->value;
                    } elseif ($customField->ui_type_id === Constants::dateOnlyUi){
                        $value = $customField->value;
                    } else {
                        $value = $customField->value;
                    }

                    $changedCustomFieldFormat[$coreKeysId] = $value;
                }
            }
        }

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
            }
        }

        return [
            'id' => (string) $this->id,
            'owner_user_id' => (string) $this->owner_user_id,
            'status' => (string) $this->status,
            'name' => (string) $this->name,
            "phone" => (string) $this->phone,
            "email" => (string) $this->email,
            "currency_id" => (string) $this->currency_id,
            'address' => (string) $this->address,
            'description' => (string) $this->description,
            'website' => (string) $this->website,
            'facebook' => (string) $this->facebook,
            'instagram' => (string) $this->instagram,
            'product_count' => (string)$product_count,
            'is_unlimited' => (string) $this->is_unlimited,
            'added_date' => (string) $this->added_date,
            'expired_date' => (string) $this->expired_date,
            'expire_status' => (string) $expireStatus,
            'added_user_id' => (string) $this->added_user_id,
            'updated_user_id' => (string) $this->updated_user_id,
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
            'logo' => new CoreImageApiResource(isset($this->logo) && $this->logo ? $this->whenLoaded('logo') : []),
            'banner_1' => new CoreImageApiResource(isset($this->banner_1) && $this->banner_1 ? $this->whenLoaded('banner_1') : []),
            'banner_2' => new CoreImageApiResource(isset($this->banner_2) && $this->banner_2 ? $this->whenLoaded('banner_2') : []),
            "vendor_relation" => $this->vendorInfo,
            'vendorBranches' => VendorBranchesWithKeyResource::collection(isset($this->vendorBranch) && count($this->vendorBranch)>0 ? $this->whenLoaded('vendorBranch'): []),
            'owner' =>  new UserWithKeyResource($this->owner),
        ] + $changedCustomFieldFormat;
    }
}
