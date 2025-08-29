<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingAndBilling extends Model
{

    use HasFactory;

    protected $fillable = [
        'id',
        'order_id',
        'ship_and_billing_date',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_postal_code',
        'is_save_shipping_info_for_next_time',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_country',
        'billing_state',
        'billing_city',
        'billing_postal_code',
        'is_save_billing_info_for_next_time',
        'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'
    ];

    protected $table = "psx_shipping_and_billings";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_shipping_and_billings";
    const userId = "user_id";
    const orderId = "order_id";
    const id = "id";
    const status = "status";
    const addedDate = 'added_date';
    const isSaveShippingInfoForNextTime = "is_save_shipping_info_for_next_time";
    const isSaveBillingInfoForNextTime = "is_save_billing_info_for_next_time";


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
