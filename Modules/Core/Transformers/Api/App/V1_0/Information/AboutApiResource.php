<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Information;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Entities\CorePrivacyPolicy;
use Modules\Core\Entities\Information\CoreDataDeletion;

class AboutApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $dataDeletionPolicy = CoreDataDeletion::first();
        $privacyPolicy = CorePrivacyPolicy::first();
        return [
            'id' => checkAndGetValue($this, 'id'),
            'about_title' => checkAndGetValue($this, 'about_title'),
            'about_description' => checkAndGetValue($this, 'about_description'),
            'about_email' => checkAndGetValue($this, 'about_email'),
            'about_phone' => checkAndGetValue($this, 'about_phone'),
            'about_address' => checkAndGetValue($this, 'about_address'),
            'about_website' => checkAndGetValue($this, 'about_website'),
            'facebook' => checkAndGetValue($this, 'facebook'),
            'google_plus' => checkAndGetValue($this, 'google_plus'),
            'instagram' => checkAndGetValue($this, 'instagram'),
            'youtube' => checkAndGetValue($this, 'youtube'),
            'pinterest' => checkAndGetValue($this, 'pinterest'),
            'twitter' => checkAndGetValue($this, 'twitter'),
            'GDPR' => checkAndGetValue($this, 'GDPR'),
            'upload_point' => checkAndGetValue($this, 'upload_point'),
            'safety_tips' => checkAndGetValue($this, 'safety_tips'),
            'faq_pages' => checkAndGetValue($this, 'faq_pages'),
            'terms_and_conditions' => checkAndGetValue($this, 'terms_and_conditions'),
            "default_photo" => new CoreImageApiResource($this->defaultPhoto ?? []),
            "privacy_policy" => $privacyPolicy ? $privacyPolicy->content:'',
            "data_deletion_policy" => $dataDeletionPolicy? $dataDeletionPolicy->content:'',
        ];
    }
}
