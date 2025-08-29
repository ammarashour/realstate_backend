<?php

namespace Modules\Core\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class StorePushNotificationMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $coreFieldFilterSettingService;

    public function __construct(CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
    }

    public function rules()
    {
        // prepare for custom field validation
        $errors = validateForCustomField(Constants::pushNotificationMessage, $this->push_noti_msg_relation);

        // prepare for core field validation
        $conds = prepareCoreFieldValidationConds(Constants::pushNotificationMessage);
        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($conds);
        $validationRules = [
            [
                'fieldName' => 'message',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'description',
                'rules' => 'required',
            ],
            [
                'fieldName' => 'cover',
                'rules' => 'nullable|sometimes|image',
            ],
        ];

        // prepared saturation for core and custom field
        $validationArr = handleValidation($errors, $coreFields, $validationRules);

        return $validationArr;
    }

    public function attributes()
    {
        $customFieldAttributeArr = handleCFAttrForValidation(Constants::blog, $this->blog_relation);

        $coreFieldAttributeArr = [
            'location_city_id' => 'Location City',
            'name' => 'Blog Name',
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
