<?php

namespace Modules\Payment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\PaymentAttributeFactory;

class PaymentAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'payment_id', 'core_keys_id', 'attribute_key', 'attribute_value', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag' ];

    protected $table = "psx_payment_attributes";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_payment_attributes";
    const id = "id";
    const paymentId = "payment_id";
    const coreKeysId = "core_keys_id";
    const attributeKey = "attribute_key";
    const attributeValue = "attribute_value";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        return PaymentAttributeFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
