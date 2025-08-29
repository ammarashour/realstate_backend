<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentInfo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionBoughtTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'user_id', 'vendor_id', 'subscription_plan_id', 'payment_method', 'price', 'razor_id', 'is_paystack', 'status','transaction_id', 'added_date', 'expired_date'];

    protected $table = "psx_subscription_bought_transactions";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_subscription_bought_transactions';
    const id = 'id';
    const userId = 'user_id';
    const vendorId = 'vendor_id';
    const subscriptionPlanId = 'subscription_plan_id';
    const paymentMethod = 'payment_method';
    const price = 'price';
    const razorId = 'razorId';
    const isPaystack = 'is_paystack';
    const status = 'status';
    const transactionId = 'transaction_id';
    const addedDate = "added_date";
    const expiredDate = "expired_date";

    protected static function newFactory()
    {
        // return \Modules\Package\Database\factories\PackageBoughtTransactionFactory::new();
    }

    public function package(){
        return $this->belongsTo(PaymentInfo::class, 'subscription_plan_id')->with(['payment', 'core_key', 'payment_info']);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->with('userRelation');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class)->with('vendorRelation');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id')->with('userRelation');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id')->with('userRelation');
    }
}
