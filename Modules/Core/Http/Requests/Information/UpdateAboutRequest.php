<?php

namespace Modules\Core\Http\Requests\Information;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;

class UpdateAboutRequest extends FormRequest
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
        $errors = validateForCustomField(Constants::about, $this->route('about_relation'));

        $coreFieldsIds = [];

        $cond['module_name'] = Constants::about;
        $cond['mandatory'] = 1;
        $cond['is_core_field'] = 1;
        $cond['enable'] = 1;

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);

        foreach ($coreFields as $key => $value){
            if (str_contains($value->field_name,"@@")) {
                $originFieldName = strstr($value->field_name,"@@",true);
            } else {
                $originFieldName = $value->field_name;
            }
            array_push($coreFieldsIds,$originFieldName);

        }

        $validationArr = [];
        $validations = array(
            array(
                'fieldName' => 'about_title',
                'rules' => 'nullable|min:3',
            ),
            array(
                'fieldName' => 'about_cover',
                'rules' => 'nullable|sometimes|image',
            ),
            array(
                'fieldName' => 'about_description',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'about_email',
                'rules' => 'nullable|sometimes|email',
            ),
            array(
                'fieldName' => 'about_phone',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'about_address',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'about_website',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'facebook',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'google_plus',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'instagram',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'youtube',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'pinterest',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'twitter',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'GDPR',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'upload_point',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'safety_tips',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'faq_pages',
                'rules' => 'nullable',
            ),
            array(
                'fieldName' => 'terms_and_conditions',
                'rules' => 'nullable',
            ),
       );

       foreach($validations as $validation){
            if(in_array($validation['fieldName'],$coreFieldsIds)){
                $validationArr[$validation['fieldName']] = 'nullable|min:3';
            } else {
                $validationArr[$validation['fieldName']] = 'nullable';
            }
       }

        // if(in_array('about_title',$coreFieldsIds)){
        //     $validationArr['about_title'] = 'nullable|min:3';
        // } else {
        //     $validationArr['about_title'] = 'nullable';
        // }

        // if(in_array('about_cover',$coreFieldsIds)){
        //     $validationArr['about_cover'] = 'nullable|sometimes|image';
        // } else {
        //     $validationArr['about_cover'] = 'nullable';
        // }

        // if(in_array('about_email',$coreFieldsIds)){
        //     $validationArr['about_email'] = 'nullable|sometimes|email';
        // } else{
        //     $validationArr['about_email'] = 'nullable';
        // }

        return $validationArr;
    }

    public function attributes()
    {
        return [
            'about_title' => "Title",
            'about_cover' => "Cover",
            'about_email' => "Email",
        ];
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
