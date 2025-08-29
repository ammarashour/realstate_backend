<?php

namespace Modules\Core\Entities\Financial;

use App\Models\User;
use Modules\Core\Entities\Delivery\Shipping;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\PaymentStatus;
use Modules\Core\Entities\Financial\TransactionCount;
use Modules\Core\Entities\Financial\TransactionDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'user_id', 'shop_id', 'sub_total_amount', 'discount_amount', 'coupon_discount_amount', 'tax_amount', 'tax_percent', 'shipping_amount', 'shipping_tax_percent', 'balance_amount', 'total_item_amount', 'total_item_count', 'contact_name', 'contact_email', 'contact_phone', 'contact_address', 'payment_method', 'transaction_status_id', 'payment_status_id', 'currency_symbol', 'currency_short_form', 'trans_code', 'memo', 'trans_lat', 'trans_lng', 'shipping_method_amount', 'shipping_method_name', 'shipping_id', 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_country', 'billing_state', 'billing_city', 'billing_postal_code', 'billing_email', 'billing_phone', 'shipping_first_name', 'sipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_postal_code', 'shipping_email', 'shipping_phone', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_transaction_headers";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_transaction_headers';
    const userId = 'user_id';
    const id = 'id';
    const transactionStatusId = 'transaction_status_id';
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\TransactionHeaderFactory::new();
    }

    public function transaction_detail(){
        return $this->hasMany(TransactionDetail::class);
    }

    public function transaction_count(){
        return $this->hasMany(TransactionCount::class);
    }

    public function transaction_status(){
        return $this->belongsTo(TransactionStatus::class);
    }

    public function payment_status(){
        return $this->belongsTo(PaymentStatus::class);
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
