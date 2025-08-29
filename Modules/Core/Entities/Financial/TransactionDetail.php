<?php

namespace Modules\Core\Entities\Financial;

use App\Models\User;
use Modules\Core\Entities\Item\Item;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Financial\TransactionHeader;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'transaction_header_id', 'shop_id', 'item_id', 'item_name', 'price', 'original_price', 'discount_amount', 'discount_value', 'qty', 'item_measurement', 'item_unit', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_transaction_details";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_transaction_details';
    const itemId = 'item_id';
    const transactionHeaderId = 'transaction_header_id';
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\TransactionDetailFactory::new();
    }

    public function item(){
        return $this->belongsTo(Item::class)->with(['category', 'subcategory', 'city', 'township', 'owner', 'editor', 'currency', 'custom_field']);
    }

    public function transaction_header(){
        return $this->belongsTo(TransactionHeader::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
