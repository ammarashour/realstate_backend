<?php

namespace Modules\Core\Transformers\Backend\Model\Authorization;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiTokenWithKeyResource extends JsonResource
{

    public function toArray($request)
    {
        $abilities = [];
        if(!empty($this->abilities)){
            $abilities = $this->abilities;
            $abilities = substr($abilities , 1);
            $abilities = substr($abilities , 0, -1);
            $abilities = str_replace('"', '',$abilities);
            $abilities = explode(",",$abilities);
        }else if($this->abilities == "[]"){
            $abilities = [];
        }else{
            $abilities = [];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tokenable_type' => $this->tokenable_type,
            'tokenable_id' => $this->tokenable_id,
            'token' => $this->token,
            'abilities' => $abilities,
            // 'last_used_at' => $this->last_used_at,
            'last_used_at' => Carbon::parse($this->last_used_at)->diffForHumans(),
            'added_date' => $this->added_date,
            'added_user_id' => $this->added_user_id,
            'added_user_id@@name' => isset($this->owner) && !empty($this->owner) ? $this->owner->name: '',
            'updated_date' => $this->updated_date,
            'updated_user_id' => $this->updated_user_id,
            'updated_user_id@@name' => isset($this->editor) && !empty($this->editor) ? $this->editor->name: '',
            'updated_flag' => $this->updated_flag,
            'authorization' => $this->authorization
        ];
    }
}
