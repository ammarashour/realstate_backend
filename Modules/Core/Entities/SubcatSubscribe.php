<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubcatSubscribe extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'cat_id', 'subcat_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_subcat_subscribes";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const user_id = 'user_id';
    const cat_id = 'cat_id';
    const subcat_id = 'subcat_id';
    const tableName = 'psx_subcat_subscribes';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\SubcatSubscribeFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
