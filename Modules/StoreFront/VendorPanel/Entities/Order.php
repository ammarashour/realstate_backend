<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\PsModel;
use App\Models\User;
use App\Policies\PsVendorPolicy;
use App\Traits\VendorAuthorizationTrait;
use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends PsModel
{

    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id', 'user_id', 'vendor_id', 'order_date', 'total_amount', 'order_status_id', 'order_code', 'is_payment_fail', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_orders";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_orders";
    const userId = "user_id";
    const id = "id";
    const orderCode = "order_code";
    const isPaymentFail = "is_payment_fail";
    const orderStatusId = "order_status_id";
    const vendorId = "vendor_id";
    const status = "status";
    const addedDate = 'added_date';

    public function __construct() {
        $this->vendorModule = Constants::vendorOrderListModule;
    }

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CategoryFactory::new();
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function orderStatus(){
        return $this->belongsTo(OrderStatus::class, "order_status_id", "id");
    }

    public function shippingAndBilling(){
        return $this->hasOne(ShippingAndBilling::class, 'order_id', 'id');
    }

    public function vendorTransaction(){
        return $this->belongsTo(VendorTransaction::class, 'id', 'order_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

}
