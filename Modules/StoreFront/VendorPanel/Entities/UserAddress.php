<?php

namespace Modules\StoreFront\VendorPanel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{

    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'postal_code',
        'is_save_shipping_info_for_next_time',
        'is_save_billing_info_for_next_time',
        'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'
    ];

    protected $table = "psx_user_addresses";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_user_addresses";
    const userId = "user_id";
    const id = "id";
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
