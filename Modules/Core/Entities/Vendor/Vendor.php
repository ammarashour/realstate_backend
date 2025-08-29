<?php

namespace Modules\Core\Entities\Vendor;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Vendor\VendorApplication;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'phone', 'email', 'address', 'description', 'website', 'facebook', 'instagram', 'status', 'owner_user_id', 'is_unlimited', 'added_date', 'expired_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendors";

    const tableName = 'psx_vendors';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const name = 'name';
    const phone = 'phone';
    const email = 'email';
    const address = 'address';
    const description = 'description';
    const website = 'website';
    const facebook = 'facebook';
    const instagram = 'instagram';
    const status = 'status';
    const ownerUserId = 'owner_user_id';
    const isUnlimited = 'is_unlimited';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';
    const expiredDate = 'expired_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorFactory::new();
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_user_id', 'id');
    }

    public function vendor_application() {
        return $this->hasOne(VendorApplication::class, 'vendor_id');
    }

    public function logo() {
        return $this->hasOne(CoreImage::class, 'img_parent_id')->where('img_type', 'vendor-logo');
    }

    public function banner_1() {
        return $this->hasOne(CoreImage::class, 'img_parent_id')->where('img_type', 'vendor-banner-1');
    }

    public function banner_2() {
        return $this->hasOne(CoreImage::class, 'img_parent_id')->where('img_type', 'vendor-banner-2');
    }

    public function vendorInfo() {
        return $this->hasMany(VendorInfo::class);
    }

    public function vendorCurrency() {
        return $this->belongsTo(ItemCurrency::class, 'currency_id', 'id');
    }

    public function vendorBranch() {
        return $this->hasMany(VendorBranch::class, 'vendor_id');
    }

    public function vendorDeliverySetting(){
        return $this->hasOne(VendorDeliverySetting::class, "vendor_id");
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
