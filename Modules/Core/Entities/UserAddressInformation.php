<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddressInformation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'first_name', 'last_name', 'company_name', 'address_1', 'address_2', 'country_name', 'state_name', 'city_name', 'postal_code', 'email', 'phone', 'is_billing', 'is_shipping', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_user_address_informations";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\UserAddressInformationFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
