<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_roles";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_roles';
    const id = 'id';
    const name = 'name';
    const description = "description";
    const status = "status";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Authorization\RoleFactory::new();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
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
