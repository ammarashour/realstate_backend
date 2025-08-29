<?php

namespace Modules\Core\Transformers\Api\App\V1_0\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\UserInfo;

class UserInfoApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $dropDownUi = Constants::dropDownUi;
        $textUi = Constants::textUi;
        $radioUi = Constants::radioUi;
        $checkBoxUi = Constants::checkBoxUi;
        $dateTimeUi = Constants::dateTimeUi;
        $textAreaUi = Constants::textAreaUi;
        $numberUi = Constants::numberUi;
        $multiSelectUi = Constants::multiSelectUi;
        $imageUi = Constants::imageUi;
        $timeOnlyUi = Constants::timeOnlyUi;
        $dateOnlyUi = Constants::dateOnlyUi;

        $data = [];
        $empty = [
            "id" => (string) "",
            "value" => (string) "",
        ];

        $changedObj = [];
        $uiTypeForAttrs = [$dropDownUi, $radioUi];
        $otherUiTypes = [$textUi, $checkBoxUi, $dateTimeUi, $textAreaUi, $numberUi, $imageUi, $timeOnlyUi, $dateOnlyUi];
        if (!empty($this->value)) {
            if (in_array($this->ui_type_id, $uiTypeForAttrs)) {
                $obj = CustomFieldAttribute::where(CustomFieldAttribute::id, $this->value)->first();
                if ($obj) {
                    $changedObj = [
                        "id" => (string) $obj->id,
                        "value" => (string) $obj->name,
                    ];
                    $data = array($changedObj);
                }
            } elseif (in_array($this->ui_type_id, $otherUiTypes)) {
                $obj = UserInfo::where(UserInfo::id, $this->id)->first();
                if ($obj) {
                    $changedObj = [
                        "id" => (string) $obj->id,
                        "value" => (string) $obj->value,
                    ];
                    $data = array($changedObj);
                }
            } elseif ($this->ui_type_id == $multiSelectUi) {
                $selectedIds = explode(',', $this->value);
                $objs = CustomFieldAttribute::whereIn(CustomFieldAttribute::id, $selectedIds)->get();
                $changedObj = [];
                foreach ($objs as $obj) {
                    $data = [
                        "id" => (string) $obj->id,
                        "value" => (string) $obj->name,
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
            'id' => isset($this->id) ? (string)$this->id : '',
            'user_id' => isset($this->user_id) ? (string)$this->user_id : '',
            'value' => isset($this->value) ? (string)$this->value : '',
            'core_keys_id' => isset($this->core_keys_id) ? (string)$this->core_keys_id : '',
            'ui_type_id' => isset($this->ui_type_id) ? (string)$this->ui_type_id : '',
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "selectedValue" => $data,
            "is_empty_object" => $this->when(!isset($this->id), 1),
        ];
    }
}
