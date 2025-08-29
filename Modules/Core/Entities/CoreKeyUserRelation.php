<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreKeyUserRelation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'core_keys_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_core_key_user_relations";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_core_key_user_relations";
    const id = "id";
    const userId = "user_id";
    const coreKeysId = "core_keys_id";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreKeyUserRelationFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
