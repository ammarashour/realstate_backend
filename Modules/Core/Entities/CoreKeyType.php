<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\CustomizeHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;

class CoreKeyType extends Model
{
    use HasFactory;

    protected $fillable = [ 'id', 'code', 'name', 'description', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag' ];

    protected $table = "psx_core_key_types";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_core_key_types';
    const id = 'id';
    const code = "code";
    const name = "name";
    const description = "description";
    const addedDate = "added_date";
    const addedUserId = "added_user_id";
    const updatedDate = "updated_date";
    const updatedUserId = "updated_user_id";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreKeyTypeFactory::new();
    }

    public function customize_header(){
        return $this->belongsTo(CustomizeHeader::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
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
