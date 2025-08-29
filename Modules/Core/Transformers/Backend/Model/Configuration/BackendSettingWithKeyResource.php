<?php

namespace Modules\Core\Transformers\Backend\Model\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class BackendSettingWithKeyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'sender_name' => (string) $this->sender_name,
            'sender_email' => (string) $this->sender_email,
            'receive_email' => (string) $this->receive_email,
            'fcm_api_key' => (string) $this->fcm_api_key,
            'map_key' => (string) $this->map_key,
            'app_token' => (string) $this->app_token,
            'topics' => (string) $this->topics,
            'topics_fe' => (string) $this->topics_fe,
            'smtp_host' => (string) $this->smtp_host,
            'smtp_port' => (string) $this->smtp_port,
            'smtp_user' => (string) $this->smtp_user,
            'smtp_pass' => (string) $this->smtp_pass,
            'smtp_encryption' => (string) $this->smtp_encryption,
            'email_verification_enabled' => (string) $this->email_verification_enabled,
            'user_social_info_override' => (string) $this->user_social_info_override,
            'landscape_width' => (string) $this->landscape_width,
            'potrait_height' => (string) $this->potrait_height,
            'square_height' => (string) $this->square_height,
            'landscape_thumb_width' => (string) $this->landscape_thumb_width,
            'potrait_thumb_height' => (string) $this->potrait_thumb_height,
            'square_thumb_height' => (string) $this->square_thumb_height,
            'landscape_thumb2x_width' => (string) $this->landscape_thumb2x_width,
            'potrait_thumb2x_height' => (string) $this->potrait_thumb2x_height,
            'square_thumb2x_height' => (string) $this->square_thumb2x_height,
            'landscape_thumb3x_width' => (string) $this->landscape_thumb3x_width,
            'potrait_thumb3x_height' => (string) $this->potrait_thumb3x_height,
            'square_thumb3x_height' => (string) $this->square_thumb3x_height,
            'search_item_limit' => (string) $this->search_item_limit,
            'search_user_limit' => (string) $this->search_user_limit,
            'search_category_limit' => (string) $this->search_category_limit,
            'dyn_link_key' => (string) $this->dyn_link_key,
            'dyn_link_url' => (string) $this->dyn_link_url,
            'dyn_link_package_name' => (string) $this->dyn_link_package_name,
            'dyn_link_domain' => (string) $this->dyn_link_domain,
            'dyn_link_deep_url' => (string) $this->dyn_link_deep_url,
            'ios_boundle_id' => (string) $this->ios_boundle_id,
            'ios_appstore_id' => (string) $this->ios_appstore_id,
            'backend_version_no' => (string) $this->backend_version_no,
            'slow_moving_item_limit' => (string) $this->slow_moving_item_limit,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => $this->getAddedUserName(),
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => $this->getupdatedUserName(),
            'updated_flag' => (string) $this->updated_flag,
            'backend_logo' => $this->backend_logo,
            'fav_icon' => $this->fav_icon,
            'backend_login_image' => $this->backend_login_image,
            'authorization' => $this->authorization
        ];
    }

    ////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////
    private function getAddedUserName()
    {
        if(empty($this->owner)){
            return "";
        }
        return $this->owner->name;
    }

    private function getupdatedUserName()
    {
        if(empty($this->editor)){
            return "";
        }
        return $this->editor->name;
    }
}
