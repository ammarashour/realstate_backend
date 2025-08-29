<?php

namespace Modules\Core\Http\Requests\Configuration;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;

class UpdateMobileSettingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function __construct(protected CoreFieldServiceInterface $coreFieldService)
    {}

    public function rules()
    {
        // prepare for custom field validation
        $errors = validateForCustomField(Constants::mobileSetting, $this->mobile_setting_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::mobileSetting);
        $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            array(
                'fieldName' => 'apple_appstore_url',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_appstore_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'google_playstore_url',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_admob',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_item_video',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'fb_key',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'date_format',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'price_format',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'default_razor_currency',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_razor_support_multi_currency',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_subcategory',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_discount',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'color_change_code',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_phone_login',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_google_login',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_apple_login',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_facebook_login',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_use_thumbnail_as_placeholder',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_use_google_map',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'item_detail_view_count_for_ads',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_ads_in_item_detail',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'after_item_count_admob_once',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'blue_mark_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'block_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'follower_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'block_slider_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'featured_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'popular_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'recent_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'category_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'default_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'discount_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'mile',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'video_duration',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_show_owner_info',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_force_login',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_language_config',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'no_filter_with_location_on_map',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'chat_image_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'profile_image_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'upload_image_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'promote_first_choice_day',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'promote_second_choice_day',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'promote_third_choice_day',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'promote_fourth_choice_day',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'default_language',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'selected_language',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'lat',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'lng',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'collection_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'shop_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_main_menu',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_special_collections',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_featured_items',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'show_best_choice_slider',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'default_flutter_wave_currency',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'default_order_time',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'trending_item_loading_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'version_no',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'version_force_update',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'version_title',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'version_message',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'version_need_clear_data',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'android_admob_banner_ad_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'android_admob_native_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'andorid_admob_interstitial_ad_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_admob_banner_ad_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_admob_native_ad_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_admob_interstitial_ad_unit_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'recent_search_keyword_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'data_config_data_source_type',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'data_config_day',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_slider_auto_play',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_demo_for_payment',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'auto_play_interval',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'loading_shimmer_item_count',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'phone_list_count',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'deli_boy_version_no',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'deli_boy_version_force_update',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'deli_boy_version_title',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'deli_boy_version_message',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'deli_boy_version_need_clear_data',
                'rules' => 'nullable',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;

    }

    /**
     * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }
}
