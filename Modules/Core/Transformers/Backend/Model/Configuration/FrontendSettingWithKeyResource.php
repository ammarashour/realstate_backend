<?php

namespace Modules\Core\Transformers\Backend\Model\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendSettingWithKeyResource extends JsonResource
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
            'id'=>(int)$this->id,
            'map_key' => (string) $this->map_key,
            'google_playstore_url' => (string) $this->google_playstore_url,
            'app_store_url' => (string) $this->app_store_url,
            'gps_enable' => (string) $this->gps_enable,
            'show_main_menu' => (string) $this->show_main_menu,
            'show_special_collections' => (string) $this->show_special_collections,
            'show_featured_items' => (string) $this->show_featured_items,
            'show_best_choice_slider' => (string) $this->show_best_choice_slider,
            'fcm_server_key' => (string) $this->fcm_server_key,
            'is_ads_on' => (string) $this->is_ads_on,
            'firebase_config' => (string) $this->firebase_config,
            'firebase_web_push_key_pair' => (string) $this->firebase_web_push_key_pair,
            'ad_client' => (string) $this->ad_client,
            'ad_slot' => (string) $this->ad_slot,
            'copyright' => (string) $this->copyright,
            'price_format' => (string) $this->price_format,
            'banner_src' => (string) $this->banner_src,
            'mile' => (string) $this->mile,
            'is_enable_video_setting' => (string) $this->is_enable_video_setting,
            'show_user_profile' => (string) $this->show_user_profile,
            'no_filter_with_location_on_map' => (string) $this->no_filter_with_location_on_map,
            'enable_notification' => (string) $this->enable_notification,
            'google_setting' => (string) $this->google_setting,
            'app_store_setting' => (string) $this->app_store_setting,
            'google_map' => (string) $this->google_map,
            'open_street_map' => (string) $this->open_street_map,
            'default_language' => (string) $this->default_language,
            // 'selected_language' => json_decode($this->selected_language),
            'selected_language' => explode(',', $this->selected_language),
            'promote_first_choice_day' => (string) $this->promote_first_choice_day,
            'promote_second_choice_day' => (string) $this->promote_second_choice_day,
            'promote_third_choice_day' => (string) $this->promote_third_choice_day,
            'promote_fourth_choice_day' => (string) $this->promote_fourth_choice_day,
            'frontend_version_no' => (string) $this->frontend_version_no,
        ];

    }
}
