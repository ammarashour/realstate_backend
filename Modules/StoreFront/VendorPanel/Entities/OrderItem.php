<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\User;
use Modules\Core\Entities\Item;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\StoreFront\VendorPanel\Entities\VendorTransaction;

class OrderItem extends Model
{

    use HasFactory;

    protected $fillable = ['id', 'order_id', 'item_id', 'quantity', 'unit_price', 'sub_total', 'discount_price', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_order_items";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_order_items";
    const orderId = "order_id";
    const id = "id";
    const itemId = "item_id";
    const addedDate = 'added_date';


    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CategoryFactory::new();
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', "id");
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
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
