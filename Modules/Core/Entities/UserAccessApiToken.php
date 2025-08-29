<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccessApiToken extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_user_access_api_tokens";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const userId = "user_id";
    const deviceId = "device_id";
    const deviceInfo = "device_info";
    const deviceToken = "device_token";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\UserAccessApiTokenFactory::new();
    }
}
