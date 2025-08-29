<?php

namespace Modules\Core\Entities\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\Financial\TransactionHeader;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'description', 'from_user_id', 'to_user_id', 'rating', 'transaction_header_id', 'shop_id', 'item_id', 'type', 'added_user_id', 'added_date', 'updated_user_id', 'updated_date', 'updated_flag'];

    protected $table = "psx_ratings";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_ratings";
    const id = "id";
    const title = "title";
    const description = "description";
    const rating = "rating";
    const fromUserId = "from_user_id";
    const toUserId = "to_user_id";
    const type = "type";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Rating\Database\factories\RatingFactory::new();
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function transactionHeader()
    {
        return $this->belongsTo(TransactionHeader::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
