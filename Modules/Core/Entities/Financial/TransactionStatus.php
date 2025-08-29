<?php

namespace Modules\Core\Entities\Financial;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Financial\TransactionHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionStatus extends Model
{
    use HasFactory;

    protected $fillable = ['id','title', 'ordering', 'color_value', 'start_stage', 'final_stage', 'is_optional', 'is_refundable', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_transaction_statuses";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\TransactionStatusFactory::new();
    }

    public function transaction_header(){
        return $this->hasOne(TransactionHeader::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
