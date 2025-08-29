<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\PsModel;
use App\Models\User;
use App\Traits\VendorAuthorizationTrait;
use Modules\Core\Constants\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPaymentStatus extends PsModel
{

    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id', 'name', 'description', 'vendor_id', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_payment_statuses";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_vendor_payment_statuses";
    const name = "name";
    const id = "id";
    const description = "description";
    const addedDate = 'added_date';
    const vendorId = 'vendor_id';
    const status = "status";

    public function __construct() {
        $this->vendorModule = Constants::vendorPaymentStatusModule;
    }

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CategoryFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

}
