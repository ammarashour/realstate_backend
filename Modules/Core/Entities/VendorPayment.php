<?php

namespace Modules\Core\Entities;

use App\Models\PsModel;
use App\Policies\PsPolicy;
use App\Traits\VendorAuthorizationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\Payment;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\VendorPaymentInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Entities\CoreKeyPaymentRelation;

class VendorPayment extends PsModel
{
    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id','payment_id','vendor_id','status','added_date','added_user_id','updated_date','updated_user_id','updated_flag'];

    protected $table = 'psx_vendor_payments';

    const tableName = 'psx_vendor_payments';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const paymentId = 'payment_id';
    const vendorId = 'vendor_id';
    const status = 'status';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    public function __construct() {
        $this->vendorModule = Constants::vendorPaymentListModule;
    }

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorPaymentFactory::new();
    }

    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function payment_relation(){
        return $this->hasMany(CoreKeyPaymentRelation::class, 'payment_id', 'payment_id')->with(['core_key','vendor_payment_infos']);
    }

    public function vendor_payment_infos()
    {
        return $this->hasMany(VendorPaymentInfo::class, 'payment_id', 'payment_id')->with('core_key');
    }
}
