<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Modules\Core\Entities\Item;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Database\factories\TouchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Touch extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type_id', 'shop_id', 'type_name', 'user_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_touches";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return TouchFactory::new();
    }

    public function item(){
        return $this->belongsTo(Item::class, 'id' , 'type_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
