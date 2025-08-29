<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Jetstream\HasTeams;
use Modules\Core\Entities\Item;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Entities\Financial\TransactionHeader;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Core\Entities\UserInfo;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Authorization\RolePermission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Core\Entities\User\Rating;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "users";
    const name = "name";
    const status = "status";
    const isBanned = "is_banned";
    const id = "id";
    const roleId = "role_id";
    const overallRating = 'overall_rating';
    const addedDate = 'added_date';
    const email = "email";
    const userPhone = 'user_phone';
    const addedUser = 'addedUser';
    const userIsSysAdmin = 'user_is_sys_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'username',
        'email',
        'password',
        'user_is_sys_admin',
        'facebook_id',
        'google_id',
        'phone_id',
        'apple_id',
        'user_phone',
        'user_address',
        'user_about_me',
        'user_cover_photo',
        'role_id',
        'status',
        'is_banned',
        'code',
        'overall_rating',
        'is_show_email',
        'is_show_phone',
        'is_shop_admin',
        'is_city_admin',
        'user_lat',
        'user_lng',
        'verify_types',
        'added_date_timestamp',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    public static function t($key)
    {
        return User::tableName . "." . $key;
    }

    public function userRelation()
    {
        return $this->hasMany(UserInfo::class);
    }

    public function user_blue_mark()
    {
        return $this->hasMany(UserInfo::class, 'user_id', 'id')->whereIn('core_keys_id', ['usr00002', 'usr00003']);
    }

    public function blue_mark()
    {
        return $this->hasMany(UserInfo::class, 'user_id', 'id')->whereIn('core_keys_id', ['usr00002']);
    }

    public function blogs()
    {
        $this->hasMany(Blog::class);
    }

    public function rating_count()
    {
        return $this->hasMany(Rating::class, 'to_user_id')->where('type', 'user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'id', 'role_id');
    }

    public function user_permissions()
    {
        return $this->hasOne(UserPermission::class, 'user_id', 'id');
    }

    public function role_permissions()
    {
        return $this->hasManyThrough(RolePermission::class, UserPermission::class, "user_id", "role_id", "id", "role_id");
    }

    public function transaction()
    {
        return $this->hasMany(TransactionHeader::class);
    }

    public function purchased_item()
    {
        return $this->hasMany(TransactionHeader::class)->where('transaction_status_id', 6); // Delivered stage (final stage)
    }

    public function sold_item()
    {
        return $this->hasMany(Item::class, 'added_user_id')->where('status', 1);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
