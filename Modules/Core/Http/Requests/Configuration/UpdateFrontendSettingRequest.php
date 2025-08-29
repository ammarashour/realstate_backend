<?php

namespace Modules\Core\Http\Requests\Configuration;

use Modules\Core\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;

class UpdateFrontendSettingRequest extends FormRequest
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
         $errors = validateForCustomField(Constants::frontendSetting, $this->frontend_setting_relation);

         // prepare for core field validation
         $conds = prepareCoreFieldValidationConds(Constants::frontendSetting);
         $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

         $validationRules = array(
             array(
                 'fieldName' => 'map_key',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'is_enable_video_setting',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'show_user_profile',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'no_filter_with_location_on_map',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'price_format',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'enable_notification',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'fcm_server_key',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'firebase_web_push_key_pair',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'firebase_config',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'ad_client',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'ad_slot',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'is_ads_on',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'copyright',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'google_playstore_url',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'google_setting',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'app_store_url',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'app_store_setting',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'banner_src',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'google_map',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'open_street_map',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'mile',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'default_language',
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
                 'fieldName' => 'gps_enable',
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
                 'fieldName' => 'frontendColors',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'frontend_version_no',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'frontend_logo',
                 'rules' => 'nullable|sometimes|image',
             ),
             array(
                 'fieldName' => 'frontend_icon',
                 'rules' => 'nullable|sometimes|image',
             ),
             array(
                 'fieldName' => 'frontend_banner',
                 'rules' => 'nullable|sometimes|image',
             ),
             array(
                 'fieldName' => 'frontend_meta_title',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'frontend_meta_description',
                 'rules' => 'nullable',
             ),
             array(
                 'fieldName' => 'frontend_meta_image',
                 'rules' => 'nullable|sometimes|image',
             ),
             array(
                 'fieldName' => 'app_branding_image',
                 'rules' => 'nullable|sometimes|image',
             ),
             array(
                 'fieldName' => 'facebook_url',
                 'rules' => 'nullable|url',
             ),
             array(
                 'fieldName' => 'youtube_url',
                 'rules' => 'nullable|url',
             ),
             array(
                 'fieldName' => 'twitter_url',
                 'rules' => 'nullable|url',
             ),
             array(
                 'fieldName' => 'linkedin_url',
                 'rules' => 'nullable|url',
             ),
             array(
                 'fieldName' => 'instagram_url',
                 'rules' => 'nullable|url',
             ),
             array(
                 'fieldName' => 'pinterest_url',
                 'rules' => 'nullable|url'
             )
         );

         // prepared saturation for core and custom field
         $validationArr = handleValidation($errors, $coreFields, $validationRules);
         return $validationArr;

     }

     public function attributes()
     {
         $customFieldAttributeArr = handleCFAttrForValidation(Constants::frontendSetting, $this->frontend_setting_relation);

         $coreFieldAttributeArr = [
             'frontend_logo' => "Frontend Logo",
             'frontend_icon' => "Frontend Icon",
             'frontend_banner' => "Frontend Banner",
             'frontend_meta_image' => "Frontend Meta Image",
             'app_branding_image' => "App Branding Image",
         ];
         $attributeArr = array_merge($coreFieldAttributeArr, $customFieldAttributeArr);

         return $attributeArr;
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
