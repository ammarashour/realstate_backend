<?php

namespace Modules\Core\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuilderSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function rules()
     {
         return [
             'token' => 'required',
             'project_url' => 'nullable',
             'builder_url' => 'nullable'
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