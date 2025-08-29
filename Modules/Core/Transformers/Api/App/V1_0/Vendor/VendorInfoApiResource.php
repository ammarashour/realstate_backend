<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Vendor;

use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorInfo;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class vendorInfoApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $data = [];
        $changedObj = [];
        $empty = [
            "id" => (string) "",
            "value" => (string) "",
        ];

        $uiTypeForAttrs = [ Constants::dropDownUi, Constants::radioUi];
        $otherUiTypes = [Constants::textUi, Constants::checkBoxUi, Constants::dateTimeUi, Constants::textAreaUi, Constants::numberUi, Constants::imageUi, Constants::timeOnlyUi, Constants::dateOnlyUi];
        if (!empty($this->value)){
            if (in_array($this->ui_type_id, $uiTypeForAttrs)) {
                $obj  = CustomFieldAttribute::where(CustomFieldAttribute::id, $this->value)->first();
                if($obj){
                    $changedObj = [
                        "id" => (string) $obj->id,
                        "value" => (string) $obj->name,
                    ];
                    $data = array($changedObj);
                }
            } elseif (in_array($this->ui_type_id, $otherUiTypes)) {
                $obj  = VendorInfo::where(VendorInfo::id, $this->id)->first();
                if ($obj) {
                    $changedObj = [
                        "id" => (string) $obj->id,
                        "value" => (string) $obj->value,
                    ];
                    $data = array($changedObj);
                }
            } elseif ($this->ui_type_id == Constants::multiSelectUi) {
                $selectedIds = explode(',', $this->value);
                $objs  = CustomFieldAttribute::whereIn(CustomFieldAttribute::id, $selectedIds)->get();
                $changedObj = [];
                foreach ($objs as $obj) {
                    $data = [
                        "id" => (string)$obj->id,
                        "value" => (string)$obj->name
                    ];
                    array_push($changedObj, $data);
                }

                $data = $changedObj;
            }
        }

        if (empty($data)) {
            $data = array($empty);
        }

        return [
            'id' => checkAndGetValue($this, 'id'),
            'vendor_id' => checkAndGetValue($this, 'vendor_id'),
            'core_keys_id' => checkAndGetValue($this, 'core_keys_id'),
            'core_key_name' => checkAndGetValue($this, 'customizeUi'),
            'isVisible' => checkAndGetValue($this, 'customizeUi'),
            'value' => checkAndGetValue($this, 'value'),
            'ui_type_id' => checkAndGetValue($this, 'ui_type_id'),
            "added_date_str" => checkAndGetValue($this, 'added_date'),
            "selectedValue" => $data
        ];
    }
}
