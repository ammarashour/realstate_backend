<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreKeyCounter extends Model
{
    use HasFactory;

    protected $fillable = [ 'id', 'code', 'counter', 'addded_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag' ];

    protected $table = "psx_core_key_counters";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const code = "code";
    const counter = "counter";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreKeyCounterFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
