<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreResetCode extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'code', 'added_user_id', 'added_date', 'updated_user_id', 'updated_date', 'updated_flag'];

    protected $table = "psx_core_reset_codes";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreResetCodeFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
