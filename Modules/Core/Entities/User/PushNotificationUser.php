<?php

namespace Modules\Core\Entities\User;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotificationUser extends PsModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'noti_id', 'user_id', 'device_token', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag', 'created_at', 'updated_at', 'deleted_at'];

    protected $table = "psx_push_notification_users";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const device_token = 'device_token';
    const noti_id = 'noti_id';
    const user_id = 'user_id';
    const tableName = 'psx_push_notification_users';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\PushNotificationUserFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
