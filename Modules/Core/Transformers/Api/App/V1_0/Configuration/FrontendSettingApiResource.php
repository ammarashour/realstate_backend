<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Configuration;
use Modules\Core\Entities\CoreImage;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;

class FrontendSettingApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        $languages = $this->getLanguages();

        return [
            'map_key' => checkAndGetValue($this, 'map_key'),
            'google_playstore_url' => checkAndGetValue($this, 'google_playstore_url'),
            'app_store_url' => checkAndGetValue($this, 'app_store_url'),
            'gps_enable' => checkAndGetValue($this, 'gps_enable'),
            'show_main_menu' => checkAndGetValue($this, 'show_main_menu'),
            'show_special_collections' => checkAndGetValue($this, 'show_special_collections'),
            'show_featured_items' => checkAndGetValue($this, 'show_featured_items'),
            'show_best_choice_slider' => checkAndGetValue($this, 'show_best_choice_slider'),
            'fcm_server_key' => checkAndGetValue($this, 'fcm_server_key'),
            'firebase_config' => $this->getFirebaseConfig(),
            'firebase_web_push_key_pair' => checkAndGetValue($this, 'firebase_web_push_key_pair'),
            'ad_client' => checkAndGetValue($this, 'ad_client'),
            'ad_slot' => checkAndGetValue($this, 'ad_slot'),
            'copyright' => checkAndGetValue($this, 'copyright'),
            'price_format' => checkAndGetValue($this, 'price_format'),
            'banner_src' => checkAndGetValue($this, 'banner_src'),
            'mile' => checkAndGetValue($this, 'mile'),
            'is_enable_video_setting' => checkAndGetValue($this, 'is_enable_video_setting'),
            'show_user_profile' => checkAndGetValue($this, 'show_user_profile'),
            'no_filter_with_location_on_map' => checkAndGetValue($this, 'no_filter_with_location_on_map'),
            'enable_notification' => checkAndGetValue($this, 'enable_notification'),
            'google_setting' => checkAndGetValue($this, 'google_setting'),
            'app_store_setting' => checkAndGetValue($this, 'app_store_setting'),
            'google_map' => (string)$backendSetting->is_google_map,
            'open_street_map' => (string)$backendSetting->is_open_street_map,
            'default_language' => $languages['defaultLanguage'],
            'exclude_language' => $languages['excludeLanguage'],
            'promote_first_choice_day' => checkAndGetValue($this, 'promote_first_choice_day'),
            'promote_second_choice_day' => checkAndGetValue($this, 'promote_second_choice_day'),
            'promote_third_choice_day' => checkAndGetValue($this, 'promote_third_choice_day'),
            'promote_fourth_choice_day' => checkAndGetValue($this, 'promote_fourth_choice_day'),
            'frontend_version_no' => checkAndGetValue($this, 'frontend_version_no'),
            'is_demo_for_payment' => checkAndGetValue($this, 'is_demo_for_payment'),
            "frontend_logo" => new CoreImageApiResource($this->frontend_logo ?? []),
            "frontend_icon" => new CoreImageApiResource($this->frontend_icon ?? []),
            "frontend_banner" => new CoreImageApiResource($this->frontend_banner ?? []),
            "app_branding_image" => new CoreImageApiResource($this->app_branding_image ?? []),
            "frontend_meta_title" => checkAndGetValue($this, 'frontend_meta_title'),
            "frontend_meta_description" => checkAndGetValue($this, 'frontend_meta_description'),
            "frontend_meta_image" => new CoreImageApiResource($this->frontend_meta_image ?? []),
            'facebook_url' => checkAndGetValue($this, 'facebook_url'),
            'linkedin_url' => checkAndGetValue($this, 'linkedin_url'),
            'twitter_url' => checkAndGetValue($this, 'twitter_url'),
            'instagram_url' => checkAndGetValue($this, 'instagram_url'),
            'pinterest_url' => checkAndGetValue($this, 'pinterest_url'),
            'youtube_url' => checkAndGetValue($this, 'youtube_url'),
            'facebook_setting' => checkAndGetValue($this, 'facebook_setting'),
            'linkedin_setting' => checkAndGetValue($this, 'linkedin_setting'),
            'twitter_setting' => checkAndGetValue($this, 'twitter_setting'),
            'instagram_setting' => checkAndGetValue($this, 'instagram_setting'),
            'pinterest_setting' => checkAndGetValue($this, 'pinterest_setting'),
            'youtube_setting' => checkAndGetValue($this, 'youtube_setting'),
       ];
    }

    /////////////////////////////////////////////////////////////////
    /// Private Functions
    /////////////////////////////////////////////////////////////////
    private function getFirebaseConfig()
    {
        if(empty($this->firebase_config)){
            $firebaseConfig = new \stdClass();
            $firebaseConfig->apiKey = '000000000000000000000000000000000000000';
            $firebaseConfig->authDomain = 'flutter-buy-and-sell.firebaseapp.com';
            $firebaseConfig->databaseURL = 'https://flutter-buy-and-sell.firebaseio.com';
            $firebaseConfig->projectId = 'flutter-buy-and-sell';
            $firebaseConfig->storageBucket = 'flutter-buy-and-sell.appspot.com';
            $firebaseConfig->messagingSenderId = '000000000000';
            $firebaseConfig->appId = '1:000000000000:web:0000000000000000000000';
            $firebaseConfig->measurementId = 'G-0000000000';

            return json_encode($firebaseConfig);
        }

        return $this->firebase_config;
    }

    private function getLanguages()
    {
        $available_languages = [
            ['language_code'=> 'en', 'country_code' => 'US', 'name' => 'English'],
            ['language_code'=> 'ar', 'country_code' => 'DZ', 'name' => 'Arabic'],
            ['language_code'=> 'hi', 'country_code' => 'IN', 'name' => 'Hindi'],
            ['language_code'=> 'de', 'country_code' => 'DE', 'name' => 'German'],
            ['language_code'=> 'es', 'country_code' => 'ES', 'name' => 'Spainish'],
            ['language_code'=> 'fr', 'country_code' => 'FR', 'name' => 'French'],
            ['language_code'=> 'id', 'country_code' => 'ID', 'name' => 'Indonesian'],
            ['language_code'=> 'it', 'country_code' => 'IT', 'name' => 'Italian'],
            ['language_code'=> 'ja', 'country_code' => 'JP', 'name' => 'Japanese'],
            ['language_code'=> 'ko', 'country_code' => 'KR', 'name' => 'Korean'],
            ['language_code'=> 'ms', 'country_code' => 'MY', 'name' => 'Malay'],
            ['language_code'=> 'pt', 'country_code' => 'PT', 'name' => 'Portuguese'],
            ['language_code'=> 'ru', 'country_code' => 'RU', 'name' => 'Russian'],
            ['language_code'=> 'th', 'country_code' => 'TH', 'name' => 'Thai'],
            ['language_code'=> 'tr', 'country_code' => 'TR', 'name' => 'Turkish'],
            ['language_code'=> 'zh', 'country_code' => 'CN', 'name' => 'Chinese'],
        ];

        $selected_language = explode(',', trim($this->selected_language));

		foreach($available_languages as $language){
			if(!in_array($language['language_code'], $selected_language)){
				$exclude_language[] = array('language_code' => $language['language_code'], 'country_code' => $language['country_code'], 'name' => $language['name']);
			}
			if($language['language_code'] == $this->default_language){
				$default_language = array('language_code' => $language['language_code'], 'country_code' => $language['country_code'], 'name' => $language['name']);
			}
		}

        return [
            'excludeLanguage' => $exclude_language,
            'defaultLanguage' => $default_language,
        ];
    }
}
