<?php

namespace Modules\Core\Entities\Item;

use App\Models\User;
use Modules\Payment\Entities\PaymentInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageBoughtTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'user_id', 'package_id', 'payment_method', 'price', 'razor_id', 'is_paystack', 'status'];

    protected $table = "psx_package_bought_transactions";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_package_bought_transactions';
    const id = 'id';
    const userId = 'user_id';
    const packageId = 'package_id';
    const paymentMethod = 'payment_method';
    const price = 'price';
    const razorId = 'razorId';
    const isPaystack = 'is_paystack';
    const status = 'status';
    const addedDate = "added_date";

    protected static function newFactory()
    {
        // return \Modules\Package\Database\factories\PackageBoughtTransactionFactory::new();
    }

    public function package(){
        return $this->belongsTo(PaymentInfo::class, 'package_id')->with(['payment', 'core_key', 'payment_info']);
    }

    public function user(){
        return $this->belongsTo(User::class)->with('userRelation');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id')->with('userRelation');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id')->with('userRelation');
    }
}
