<?php

namespace Modules\Core\Transformers\Backend\NoModel\Vendor;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorInfoApiResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Backend\Model\User\UserWithKeyResource;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Str;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorBranchesWithKeyResource;

class VendorRejectWithKeyResource extends JsonResource
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
        foreach ($customFields as $customField){
            if (!empty($customField->customizeUi)){
                if ($customField->customizeUi->enable === 1 && $customField->customizeUi->is_delete === 0){

                    $coreKeysId = $customField->core_keys_id;
                    $value = '';
                    if ($customField->ui_type_id === Constants::dropDownUi){
                        foreach ($customizeDetails as $customizeDetail){
                            if ($customizeDetail->id == $customField->value){
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id."@@name";
                        $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;

                    } elseif ($customField->ui_type_id === Constants::radioUi){
                        foreach ($customizeDetails as $customizeDetail){
                            if ($customizeDetail->id == $customField->value){
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id."@@name";
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

        return [
            'id' => (string) $this->id,
            'owner_user_id' => (string) $this->owner_user_id,
            'status' => (string) $this->status,
            'name' => (string) $this->name,
            "phone" => (string) $this->phone,
            "email" => (string) $this->email,
            'address' => (string) $this->address,
            'description' => (string) $this->description,
            'website' => (string) $this->website,
            'facebook' => (string) $this->facebook,
            'instagram' => (string) $this->instagram,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'updated_user_id' => (string) $this->updated_user_id,
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            'updated_flag' => (string) $this->updated_flag,
            'logo' => new CoreImageApiResource(isset($this->logo) && $this->logo ? $this->whenLoaded('logo'): []),
            'banner_1' => new CoreImageApiResource(isset($this->banner_1) && $this->banner_1 ? $this->whenLoaded('banner_1'): []),
            'banner_2' => new CoreImageApiResource(isset($this->banner_2) && $this->banner_2 ? $this->whenLoaded('banner_2'): []),
            "vendor_relation" => $this->vendorInfo,
            'vendorBranches' => VendorBranchesWithKeyResource::collection(isset($this->vendorBranch) && count($this->vendorBranch)>0 ? $this->whenLoaded('vendorBranch'): []),
            'owner' => new UserWithKeyResource(isset($this->owner) && $this->owner ? $this->whenLoaded('owner'): []),
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::rejectVendorModule, Auth::id())
        ]+ $changedCustomFieldFormat;
    }
}
