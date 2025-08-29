<?php

namespace Modules\Core\Entities\Notification;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

class PushNotificationMessage extends PsModel
{
    use HasFactory;

    protected $fillable = ['id', 'message', 'description', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_push_notification_messages";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_push_notification_messages";
    const id = "id";
    const message = "message";
    const description = "description";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\PushNotificationMessage\Database\factories\PushNotificationMessageFactory::new();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function cover()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id')->where('img_type', 'push_notification_message');
    }

    public function defaultPhoto(){
        return $this->hasOne(CoreImage::class, 'img_parent_id','id')->where('img_type','push_notification_message');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

//    public function toArray()
//    {
//        return parent::toArray() + [
//            'authorizations' => $this->authorizations(['update','delete','create'])
//        ];
//    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
