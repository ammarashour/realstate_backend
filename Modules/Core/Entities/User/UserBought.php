<?php

namespace Modules\Core\Entities\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBought extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'item_id', 'buyer_user_id', 'seller_user_id', 'added_date', 'added_user_id', 'updated_user_id', 'updated_date', 'updated_flag'];

    protected $table = "psx_user_boughts";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_user_boughts";
    const id = "id";
    const itemId = "item_id";
    const buyerUserId = "buyer_user_id";
    const sellerUserId = "seller_user_id";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Chat\Database\factories\UserBoughtFactory::new();
    }

    public static function t($key) {
        return UserBought::tableName. "." . $key;
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
