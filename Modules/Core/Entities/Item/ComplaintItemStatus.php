<?php

namespace Modules\Core\Entities\Item;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplaintItemStatus extends Model
{
    use HasFactory;

    protected $fillable = [];

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $table = 'psx_reported_item_statuses';

    const id = 'id';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\ComplaintItemStatusFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
