<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Financial\TransactionHeader;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_payment_statuses";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\PaymentStatusFactory::new();
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
