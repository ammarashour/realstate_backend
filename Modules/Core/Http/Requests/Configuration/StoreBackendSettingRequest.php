<?php

namespace Modules\Core\Http\Requests\Configuration;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;

class StoreBackendSettingRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::backendSetting, $this->backend_setting_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::backendSetting);
        $coreFields = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $validationRules = array(
            array(
                'fieldName' => 'sender_name',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'sender_email',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'receive_email',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'fcm_api_key',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'map_key',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'app_token',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'topics',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'topics_fe',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'smtp_host',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'smtp_port',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'smtp_user',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'smtp_pass',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'smtp_encryption',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'email_verification_enabled',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'user_social_info_override',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'landscape_width',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'potrait_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'square_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'landscape_thumb_width',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'potrait_thumb_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'square_thumb_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'landscape_thumb2x_width',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'potrait_thumb2x_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'square_thumb2x_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'landscape_thumb3x_width',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'potrait_thumb3x_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'square_thumb3x_height',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'dyn_link_key',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'dyn_link_url',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'dyn_link_package_name',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'dyn_link_domain',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'dyn_link_deep_url',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_boundle_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'ios_appstore_id',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'backend_version_no',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'slow_moving_item_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'search_item_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'search_user_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'search_category_limit',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'date_format',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'backend_logo',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'fav_icon',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'backend_login_image',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'backend_water_mask_image',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'water_mask_background',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'watermask_image_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'font_size',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'position',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'upload_setting',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'opacity',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'commonColor',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'watermask_title',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_watermask',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'watermask_angle',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'padding',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_google_map',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'is_open_street_map',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'fe_setting',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'vendor_setting',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'firebasePrivateKeyJsonFile',
                'rules' => 'nullable|file|mimetypes:application/json',
            ),
        );

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);
        return $validationArr;

    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::backendSetting, $this->backend_setting_relation);

        $coreFieldAttributeArr = [
            'backend_logo' => "Backend Logo",
            'fav_icon' => "Fav Icon",
            'backend_login_image' => "Backend Login Image",
            'backend_water_mask_image' => "Backend Water Mask Image",
            'water_mask_background' => "Water Mask Background",
            'is_watermask' => "Is Watermask",
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
