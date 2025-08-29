<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\PsModel;
use App\Models\User;
use App\Policies\PsVendorPolicy;
use App\Traits\VendorAuthorizationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends PsModel
{

    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id', 'name', 'description', 'vendor_id', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_order_statuses";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_order_statuses";
    const name = "name";
    const id = "id";
    const vendorId = "vendor_id";
    const status = "status";
    const description = "description";
    const addedDate = 'added_date';

    public function __construct() {
        $this->vendorModule = Constants::vendorOrderStatusModule;
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
