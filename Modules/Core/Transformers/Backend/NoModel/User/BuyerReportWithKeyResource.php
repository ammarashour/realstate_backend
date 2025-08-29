<?php

namespace Modules\Core\Transformers\Backend\NoModel\User;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\User\Rating;

class BuyerReportWithKeyResource extends JsonResource
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
            'email' => $this->email,
            'facebook_id' => $this->facebook_id,
            'google_id' => $this->google_id,
            'phone_id' => $this->phone_id,
            'apple_id' => $this->apple_id,
            'user_phone' => $this->user_phone,
            'user_address' => $this->user_address,
            'user_about_me' => $this->user_about_me,
            'user_cover_photo' => $this->getCoverPhoto(),
            'role_id' => $this->role_id,
            'role_id@@name' => $this->getRoleName(),
            'status' => $this->status,
            'is_banned' => $this->is_banned,
            'code' => $this->code,
            'overall_rating' => $this->overall_rating,
            "rating_count" => $this->getRatingCount(),
            'is_show_email' => $this->is_show_email,
            'is_show_phone' => $this->is_show_phone,
            'is_shop_admin' => $this->is_shop_admin,
            'is_city_admin' => $this->is_city_admin,
            'user_lat' => $this->user_lat,
            'user_lng' => $this->user_lng,
            'verify_types' => $this->verify_types,
            'purchased_item_count' => $this->purchased_item_count,
            'added_date_timestamp' => $this->added_date_timestamp,
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user_id@@name' => $this->getAddedUserName(),
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'udpated_user_id@@name' => $this->getUpdatedUserName(),
            'updated_flag' => $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::buyerReportModule, Auth::id())
        ] + $this->changedCustomFieldFormat();
    }

    private function getCoverPhoto()
    {

        if (file_exists(public_path() . '/' . Constants::originPath . $this->user_cover_photo)) {
            return $this->user_cover_photo;
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

    private function getRatingCount()
    {

        if (empty($this->id)) {
            return '';
        }

        return (string) (Rating::where(['to_user_id' => $this->id])->count());
    }

    private function changedCustomFieldFormat()
    {
        $changedCustomFieldFormat = [];
        $customizeDetails = CustomFieldAttribute::latest()->get();

        $customFields = $this->userRelation;

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
