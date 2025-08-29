<?php

namespace Modules\Core\Entities\User;

use App\Models\User;
use Modules\Core\Entities\UserInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowUser extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'followed_user_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_follow_users";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_follow_users";
    const id = "id";
    const userId = "user_id";
    const followedUserId = "followed_user_id";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\FollowUser\Database\factories\FollowUserFactory::new();
    }

    public static function t($key) {
        return FollowUser::tableName.".".$key;
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function userRelation(){
        return $this->hasMany(UserInfo::class, 'user_id');
    }
}
