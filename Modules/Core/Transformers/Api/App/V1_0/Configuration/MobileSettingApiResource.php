<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Localization\MobileLanguage;
use Modules\Core\Transformers\Api\App\V1_0\Localization\MobileLanguageApiResource;

class MobileSettingApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $included_language = MobileLanguage::where(MobileLanguage::enable, 1)->get();
        $default_language = MobileLanguage::where(MobileLanguage::status, 1)->first();

        return [
            'apple_appstore_url' => checkAndGetValue($this, 'apple_appstore_url'),
            'ios_appstore_id' => checkAndGetValue($this, 'ios_appstore_id'),
            'google_playstore_url' => checkAndGetValue($this, 'google_playstore_url'),
            'is_show_admob' => checkAndGetValue($this, 'is_show_admob'),
            'fb_key' => checkAndGetValue($this, 'fb_key'),
            'date_format' => checkAndGetValue($this, 'date_format'),
            'price_format' => checkAndGetValue($this, 'price_format'),
            'default_razor_currency' => checkAndGetValue($this, 'default_razor_currency'),
            'is_razor_support_multi_currency' => checkAndGetValue($this, 'is_razor_support_multi_currency'),
            'is_show_subcategory' => checkAndGetValue($this, 'is_show_subcategory'),
            'is_show_discount' => checkAndGetValue($this, 'is_show_discount'),
            'show_phone_login' => checkAndGetValue($this, 'show_phone_login'),
            'show_google_login' => checkAndGetValue($this, 'show_google_login'),
            'show_apple_login' => checkAndGetValue($this, 'show_apple_login'),
            'show_facebook_login' => checkAndGetValue($this, 'show_facebook_login'),
            //'is_show_token_id' => isset($this->is_show_token_id) ? (string) $this->is_show_token_id : '',
            'is_use_thumbnail_as_placeholder' => checkAndGetValue($this, 'is_use_thumbnail_as_placeholder'),
            'is_use_googlemap' => checkAndGetValue($this, 'is_use_google_map'),
            'is_show_item_video' => checkAndGetValue($this, 'is_show_item_video'),
            'item_detail_view_count_for_ads' => checkAndGetValue($this, 'item_detail_view_count_for_ads'),
            'is_show_ads_in_item_detail' => checkAndGetValue($this, 'is_show_ads_in_item_detail'),
            'after_item_count_admob_once' => checkAndGetValue($this, 'after_item_count_admob_once'),
            'blue_mark_size' => checkAndGetValue($this, 'blue_mark_size'),
            'block_item_loading_limit' => checkAndGetValue($this, 'block_item_loading_limit'),
            'follower_item_loading_limit' => checkAndGetValue($this, 'follower_item_loading_limit'),
            'block_slider_loading_limit' => checkAndGetValue($this, 'block_slider_loading_limit'),
            'feature_item_loading_limit' => checkAndGetValue($this, 'featured_item_loading_limit'),
            'popular_item_loading_limit' => checkAndGetValue($this, 'popular_item_loading_limit'),
            'recent_item_loading_limit' => checkAndGetValue($this, 'recent_item_loading_limit'),
            'category_loading_limit' => checkAndGetValue($this, 'category_loading_limit'),
            'default_loading_limit' => checkAndGetValue($this, 'default_loading_limit'),
            'discount_item_loading_limit' => checkAndGetValue($this, 'discount_item_loading_limit'),
            'mile' => checkAndGetValue($this, 'mile'),
            'video_duration' => checkAndGetValue($this, 'video_duration'),
            'is_show_owner_info' => checkAndGetValue($this, 'is_show_owner_info'),
            'is_force_login' => checkAndGetValue($this, 'is_force_login'),
            'is_language_config' => checkAndGetValue($this, 'is_language_config'),
            'no_filter_with_location_on_map' => checkAndGetValue($this, 'no_filter_with_location_on_map'),
            'chat_image_size' => checkAndGetValue($this, 'chat_image_size'),
            'profile_image_size' => checkAndGetValue($this, 'profile_image_size'),
            'upload_image_size' => checkAndGetValue($this, 'upload_image_size'),
            'promote_first_choice_day' => checkAndGetValue($this, 'promote_first_choice_day'),
            'promote_second_choice_day' => checkAndGetValue($this, 'promote_second_choice_day'),
            'promote_third_choice_day' => checkAndGetValue($this, 'promote_third_choice_day'),
            'promote_fourth_choice_day' => checkAndGetValue($this, 'promote_fourth_choice_day'),
            "default_language" => new MobileLanguageApiResource($default_language ?? []),
            'included_language' => MobileLanguageApiResource::collection($included_language ?? []),
            'lat' => checkAndGetValue($this, 'lat'),
            'lng' => checkAndGetValue($this, 'lng'),
            'collection_item_loading_limit' => checkAndGetValue($this, 'collection_item_loading_limit'),
            'shop_loading_limit' => checkAndGetValue($this, 'shop_loading_limit'),
            'show_main_menu' => checkAndGetValue($this, 'show_main_menu'),
            'show_special_collections' => checkAndGetValue($this, 'show_special_collections'),
            'show_featured_items' => checkAndGetValue($this, 'show_featured_items'),
            'show_best_choice_slider' => checkAndGetValue($this, 'show_best_choice_slider'),
            'default_flutter_wave_currency' => checkAndGetValue($this, 'default_flutter_wave_currency'),
            'default_order_time' => checkAndGetValue($this, 'default_order_time'),
            'trending_item_loading_limit' => checkAndGetValue($this, 'trending_item_loading_limit'),
            'version_no' => checkAndGetValue($this, 'version_no'),
            'version_force_update' => checkAndGetValue($this, 'version_force_update'),
            'version_title' => checkAndGetValue($this, 'version_title'),
            'version_message' => checkAndGetValue($this, 'version_message'),
            'version_need_clear_data' => checkAndGetValue($this, 'version_need_clear_data'),

            'android_admob_banner_ad_unit_id' => checkAndGetValue($this, 'android_admob_banner_ad_unit_id'),
            'android_admob_native_unit_id' => checkAndGetValue($this, 'android_admob_native_unit_id'),
            'andorid_admob_interstitial_ad_unit_id' => checkAndGetValue($this, 'andorid_admob_interstitial_ad_unit_id'),
            'ios_admob_banner_ad_unit_id' => checkAndGetValue($this, 'ios_admob_banner_ad_unit_id'),
            'ios_admob_native_ad_unit_id' => checkAndGetValue($this, 'ios_admob_native_ad_unit_id'),
            'ios_admob_interstitial_ad_unit_id' => checkAndGetValue($this, 'ios_admob_interstitial_ad_unit_id'),
            'recent_search_keyword_limit' => checkAndGetValue($this, 'recent_search_keyword_limit'),
            'data_config_data_source_type' => checkAndGetValue($this, 'data_config_data_source_type'),
            'data_config_day' => checkAndGetValue($this, 'data_config_day'),
            'is_slider_auto_play' => checkAndGetValue($this, 'is_slider_auto_play'),
            'is_demo_for_payment' => checkAndGetValue($this, 'is_demo_for_payment'),
            'auto_play_interval' => checkAndGetValue($this, 'auto_play_interval'),
            'loading_shimmer_item_count' => checkAndGetValue($this, 'loading_shimmer_item_count'),
            'phone_list_count' => checkAndGetValue($this, 'phone_list_count'),

            'deli_boy_version_no' => checkAndGetValue($this, 'deli_boy_version_no'),
            'deli_boy_version_force_update' => checkAndGetValue($this, 'deli_boy_version_force_update'),
            'deli_boy_version_title' => checkAndGetValue($this, 'deli_boy_version_title'),
            'deli_boy_version_message' => checkAndGetValue($this, 'deli_boy_version_message'),
            'deli_boy_version_need_clear_data' => checkAndGetValue($this, 'deli_boy_version_need_clear_data'),
            'color_change_code' => checkAndGetValue($this, 'color_change_code'),
            'theme_component_attr_change_code' => checkAndGetValue($this, 'theme_component_attr_change_code'),
        ];
    }
}
