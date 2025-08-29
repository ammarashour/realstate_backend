<?php

namespace Modules\Core\Entities\Configuration;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdPostType extends PsModel
{
    use HasFactory;

    protected $fillable = ['id', 'key', 'value', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_ad_post_types";

    const id = "id";
    const key = "key";
    const value = "value";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\AdPostTypeFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
