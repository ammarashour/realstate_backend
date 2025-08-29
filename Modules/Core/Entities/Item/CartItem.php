<?php

namespace Modules\Core\Entities\Item;

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
use Modules\Core\Entities\Item;
use Modules\StoreFront\VendorPanel\Entities\Cart;

class CartItem extends PsModel
{

    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id', 'cart_id', 'item_id', 'quantity', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_cart_items";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_cart_items";
    const itemId = "item_id";
    const id = "id";
    const cartId = "cart_id";
    const quantity = "quantity";
    const addedDate = 'added_date';
    const addedUserId = 'added_user_id';

    public function __construct() {
        $this->vendorModule = Constants::vendorOrderListModule;
    }

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CategoryFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function cart(){
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }


}
