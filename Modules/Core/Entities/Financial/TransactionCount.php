<?php

namespace Modules\Core\Entities\Financial;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Financial\TransactionHeader;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionCount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'transaction_header_id', 'item_id', 'category_id', 'subcategory_id', 'user_id', 'added_user_id', 'added_date', 'updated_user_id', 'updated_date', 'updated_flag'];

    protected $table = "psx_transaction_counts";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\TransactionCountFactory::new();
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
