<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Entities\Vendor\Vendor;

class VendorTransaction extends Model
{

    use HasFactory;

    protected $fillable = ['id', 'order_id', 'currency_id', 'user_id', 'vendor_id', 'payment_date', 'payment_amount', 'payment_method', 'razor_id', 'is_paystack', 'vendor_payment_status_id', 'transaction_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_transactions";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_vendor_transactions";
    const userId = "user_id";
    const id = "id";
    const orderId = "order_id";
    const currencyId = "currency_id";
    const vendorId = "vendor_id";
    const paymentDate = "payment_date";
    const paymentAmount = "payment_amount";
    const paymentMethod = "payment_method";
    const razorId = "razor_id";
    const isPaystack = "is_paystack";
    const vendorPaymentStatusId = "vendor_payment_status_id";
    const transactionId = "transaction_id";
    const addedDate = 'added_date';
    const addedUserId = "added_user_id";
    const updatedDate = "updated_date";
    const updatedUserId = "updated_user_id";
    const updatedFlag = "updated_flag";


    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CategoryFactory::new();
    }

    public function currency(){
        return $this->belongsTo(ItemCurrency::class, "currency_id", "id");
    }

    public function vendorPaymentStatus(){
        return $this->belongsTo(VendorPaymentStatus::class, "vendor_payment_status_id", "id");
    }

    public function order(){
        return $this->belongsTo(Order::class, "order_id", "id");
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, "vendor_id", "id");
    }
    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

//    public function toArray()
//    {
//        return parent::toArray() + [
//                'authorizations' => $this->authorizations(['update','delete','create'])
//            ];
//    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }


}
