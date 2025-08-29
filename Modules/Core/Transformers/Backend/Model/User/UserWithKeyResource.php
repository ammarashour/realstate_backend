<?php

namespace Modules\Core\Transformers\Backend\Model\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

class UserWithKeyResource extends JsonResource
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
            'id' => checkAndGetValue($this, 'id'),
            'name' => checkAndGetValue($this, 'name'),
            'email' => checkAndGetValue($this, 'email'),
            'facebook_id' => checkAndGetValue($this, 'facebook_id'),
            'google_id' => checkAndGetValue($this, 'google_id'),
            'phone_id' => checkAndGetValue($this, 'phone_id'),
            'apple_id' => checkAndGetValue($this, 'apple_id'),
            'user_phone' => checkAndGetValue($this, 'user_phone'),
            'user_address' => checkAndGetValue($this, 'user_address'),
            'user_about_me' => checkAndGetValue($this, 'user_about_me'),
            'user_cover_photo' => $this->getCoverPhoto(),
            'role_id' => checkAndGetValue($this, 'role_id'),
            'role_id@@name' => $this->getRoleName(),
            'status' => checkAndGetValue($this, 'status'),
            'is_banned' => checkAndGetValue($this, 'is_banned'),
            'code' => checkAndGetValue($this, 'code'),
            'user_is_sys_admin' => checkAndGetValue($this, 'user_is_sys_admin'),
            'overall_rating' => checkAndGetValue($this, 'overall_rating'),
            'is_show_email' => checkAndGetValue($this, 'is_show_email'),
            'is_show_phone' => checkAndGetValue($this, 'is_show_phone'),
            'is_shop_admin' => checkAndGetValue($this, 'is_shop_admin'),
            'is_city_admin' => checkAndGetValue($this, 'is_city_admin'),
            'user_lat' => checkAndGetValue($this, 'user_lat'),
            'user_lng' => checkAndGetValue($this, 'user_lng'),
            'verify_types' => checkAndGetValue($this, 'verify_types'),
            'added_date_timestamp' => checkAndGetValue($this, 'added_date_timestamp'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_user_id' => checkAndGetValue($this, 'added_user_id'),
            'added_user_id@@name' => $this->getAddedUserName(),
            'updated_date' => checkAndGetValue($this, 'updated_date'),
            'updated_user_id' => checkAndGetValue($this, 'updated_user_id'),
            'udpated_user_id@@name' => $this->getUpdatedUserName(),
            'updated_flag' => checkAndGetValue($this, 'updated_flag'),
            'authorizations' => checkAndGetValue($this, 'id') ? $this->authorization : ''
        ] + $this->changedCustomFieldFormat();
    }

    private function getCoverPhoto()
    {

        if (file_exists(public_path() . '/' . Constants::originPath . checkAndGetValue($this, 'user_cover_photo'))) {
            return checkAndGetValue($this, 'user_cover_photo');
        }

        return 'default_profile.png';
    }

    private function getRoleName()
    {

        if (empty($this->role)) {
            return '';
        }

        return $this->role->name;
    }

    private function getAddedUserName()
    {

        if (empty($this->owner)) {
            return '';
        }

        return $this->owner->name;
    }

    private function getUpdatedUserName()
    {

        if (empty($this->editor)) {
            return '';
        }

        return $this->editor->name;
    }

    private function changedCustomFieldFormat()
    {
        $changedCustomFieldFormat = [];
        $customizeDetails = CustomFieldAttribute::latest()->get();

        $customFields = empty($this->userRelation) ? [] : $this->userRelation ;

        foreach ($customFields as $customField) {

            if (isset($customField->customizeUi) && $customField->customizeUi->enable == 1 && $customField->customizeUi->is_delete == 0) {

                $coreKeysId = $customField->core_keys_id;
                $value = '';
                if ($customField->ui_type_id === Constants::dropDownUi) {
                    foreach ($customizeDetails as $customizeDetail) {
                        if ($customizeDetail->id == $customField->value) {
                            $value = $customizeDetail->name;
                        }
                    }
                    $coreKeysId = $customField->core_keys_id . "@@name";
                    $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;
                } elseif ($customField->ui_type_id === Constants::radioUi) {
                    foreach ($customizeDetails as $customizeDetail) {
                        if ($customizeDetail->id == $customField->value) {
                            $value = $customizeDetail->name;
                        }
                    }
                    $coreKeysId = $customField->core_keys_id . "@@name";
                    $changedCustomFieldFormat[$customField->core_keys_id] = $customField->value;
                } elseif ($customField->ui_type_id === Constants::dateTimeUi) {
                    $value = $customField->value->format('d M Y, h : i');
                } elseif ($customField->ui_type_id === Constants::textAreaUi) {
                    $value = Str::words($customField->value, 5, '...');
                } elseif ($customField->ui_type_id === Constants::timeOnlyUi) {
                    $value = $customField->value;
                } elseif ($customField->ui_type_id === Constants::dateTimeUi) {
                    $value = $customField->value->format('d M Y');
                } else {
                    $value = $customField->value;
                }
                $changedCustomFieldFormat[$coreKeysId] = $value;
            }
        }

        return $changedCustomFieldFormat;
    }
}
