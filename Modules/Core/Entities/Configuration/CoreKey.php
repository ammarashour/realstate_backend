<?php

namespace Modules\Core\Entities\Configuration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreKey extends Model
{
    use HasFactory;

    protected $fillable = [ 'id', 'core_keys_id', 'name', 'description', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag' ];

    protected $table = "psx_core_keys";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const tableName = "psx_core_keys";
    const coreKeysId = 'core_keys_id';
    const name = "name";
    const description = "description";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreKeyFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
