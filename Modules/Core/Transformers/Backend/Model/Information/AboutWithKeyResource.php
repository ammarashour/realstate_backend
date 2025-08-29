<?php

namespace Modules\Core\Transformers\Backend\Model\Information;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutWithKeyResource extends JsonResource
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
            'id' => (string) $this->id,
            'about_title' => (string)$this->about_title,
            'about_description' => (string)$this->about_description,
            'about_email' => (string)$this->about_email,
            'about_phone' => (string)$this->about_phone,
            'about_address' => (string)$this->about_address,
            'about_website' => (string)$this->about_website,
            'facebook' => (string)$this->facebook,
            'google_plus' => (string)$this->google_plus,
            'instagram' => (string)$this->instagram,
            'youtube' => (string)$this->youtube,
            'pinterest' => (string)$this->pinterest,
            'twitter' => (string)$this->twitter,
            'GDPR' => (string)$this->GDPR,
            'upload_point' => (string)$this->upload_point,
            'safety_tips' => (string)$this->safety_tips,
            'faq_pages' => (string)$this->faq_pages,
            'terms_and_conditions' => (string)$this->terms_and_conditions,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
        ];

    }
}
