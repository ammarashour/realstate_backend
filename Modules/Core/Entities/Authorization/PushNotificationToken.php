<?php

namespace Modules\Core\Entities\Authorization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PushNotificationToken extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'device_token', 'platform_name', 'user_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_push_notification_tokens";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const device_token = 'device_token';
    const platform_name = 'platform_name';
    const user_id = 'user_id';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\PushNotificationTokenFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
