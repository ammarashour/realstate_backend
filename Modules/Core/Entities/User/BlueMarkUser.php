<?php

namespace Modules\Core\Entities\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlueMarkUser extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'added_user_id', 'added_date', 'updated_user_id', 'updated_date', 'updated_flag'];

    protected $table = "psx_blue_mark_users";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_blue_mark_users";
    const id = "id";
    const userId = "user_id";
    const addedDate = 'added_date';
    const moduleName = 'module_name';

    protected static function newFactory()
    {
        // return \Modules\BlueMarkUser\Database\factories\BlueMarkUserFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
