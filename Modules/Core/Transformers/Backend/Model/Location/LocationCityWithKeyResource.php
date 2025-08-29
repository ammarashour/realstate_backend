<?php

namespace Modules\Core\Transformers\Backend\Model\Location;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

class LocationCityWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {


        $dateTimeUi = Constants::dateTimeUi;
        $textAreaUi = Constants::textAreaUi;
        $dateOnlyUi = Constants::dateOnlyUi;

        $changedCustomFieldFormat = [];
        $customizeDetails = CustomFieldAttribute::latest()->get();

        $customFields = $this->cityRelation;
        foreach ($customFields as $customField) {
            if (! empty($customField->customizeUi)) {
                if ($customField->customizeUi->enable === 1 && $customField->customizeUi->is_delete === 0) {

                    $coreKeysId = $customField->core_keys_id;
                    $value = '';
                    if ($customField->ui_type_id === Constants::dropDownUi) {
                        foreach ($customizeDetails as $customizeDetail) {
                            if ($customizeDetail->id == $customField->value) {
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id . '@@name';
                        $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;
                    } elseif ($customField->ui_type_id === Constants::radioUi) {
                        foreach ($customizeDetails as $customizeDetail) {
                            if ($customizeDetail->id == $customField->value) {
                                $value = $customizeDetail->name;
                            }
                        }
                        $coreKeysId = $customField->core_keys_id . '@@name';
                        $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;
                    } elseif ($customField->ui_type_id === $dateTimeUi) {
                        $dateTime = Carbon::parse($customField->value);
                        $value = $dateTime->format('d M Y, h : i');
                    } elseif ($customField->ui_type_id === $textAreaUi) {
                        $value = Str::words($customField->value, 5, '...');
                    } elseif ($customField->ui_type_id === Constants::timeOnlyUi) {
                        $value = $customField->value;
                    } elseif ($customField->ui_type_id === $dateOnlyUi) {
                        $dateOnly = Carbon::parse($customField->value);
                        $value = $dateOnly->format('d M Y');
                    } else {
                        $value = $customField->value;
                    }

                    $changedCustomFieldFormat[$coreKeysId] = $value;
                }
            }
        }

        return [
            'id' => (string) $this->id,
            'name' => (string) $this->name,
            'lat' => (string) $this->lat,
            'lng' => (string) $this->lng,
            'ordering' => (string) $this->ordering,
            'status' => (string) $this->status,
            'description' => (string) $this->description,
            'touch_count' => (string) $this->touch_count,
            'is_featured' => (string) $this->is_featured,
            'featured_date' => (string) $this->featured_date,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => ! empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => (string) $this->updated_flag,
            'authorization' => $this->authorization,
        ] + $changedCustomFieldFormat;
    }
}
