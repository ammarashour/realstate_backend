<?php

namespace Modules\Core\Transformers\Api\App\V1_0\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;


class CheckUserApiResource extends JsonResource
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
        if($backendSetting->email_verification_enabled == 1 && isset($this->code) && $this->code != '' && $this->code != null){
            $need_verify = '1';
        }else{
            $need_verify = '0';
        }

        return [
            'id' => isset($this->id)?(string)$this->id:'',

            'email_verified_at' => isset($this->email_verified_at)?(string)$this->email_verified_at:'',
            'facebook_id' => isset($this->facebook_id)?(string)$this->facebook_id:'',
            'google_id' => isset($this->google_id)?(string)$this->google_id:'',
            'phone_id' => isset($this->phone_id)?(string)$this->phone_id:'',
            'apple_id' => isset($this->apple_id)?(string)$this->apple_id:'',
            'name' => isset($this->name)?(string)$this->name:'',
            'email' => isset($this->email)?(string)$this->email:'',
            'username' => isset($this->username)?(string)$this->username:'',
            'user_phone' => isset($this->user_phone)?(string)$this->user_phone:'',
            'hasPassword'=>isset($this->password)?(string)"true":"false",
            'need_verify' => $need_verify,
            // 'user_address' => isset($this->user_address)?(string)$this->user_address:'',
            // 'user_password' => isset($this->user_password)?(string)$this->user_password:'',


        ];
    }
}
