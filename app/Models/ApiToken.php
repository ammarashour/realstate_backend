<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PsModel;
use Illuminate\Support\Facades\Gate;
/**
 * @deprecated
 */
class ApiToken extends PsModel
{
    use HasFactory;

    const table = "personal_access_tokens";
    const tokenableType = "tokenable_type";
    const tokenableId = "tokenable_id";
    const name = "name";
    const id = "id";
    const token = "token";
    const abilities = 'abilities';
    const lastUsedAt = 'last_used_at';
    const addedDate = 'added_date';
    const addedUserId = 'added_user_id';
    const updatedDate = 'updated_date';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    protected $fillable = [
        'id','tokenable_type', 'tokenable_id', 'name', 'token', 'abilities', 'last_user_at', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'
    ];

    protected $table = 'personal_access_tokens';

    protected $hidden = [
        'token',
    ];

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
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }

}
