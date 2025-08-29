<?php

namespace Modules\Payment\Entities;

use App\Models\User;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Item\PackageBoughtTransaction;

class PaymentInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'payment_id', 'core_keys_id', 'value', 'shop_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_payment_infos";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_payment_infos";
    const id = "id";
    const paymentId = "payment_id";
    const coreKeysId = "core_keys_id";
    const value = "value";
    const shopId = "shop_id";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Payment\Database\factories\PaymentInfoFactory::new();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function core_key()
    {
        return $this->belongsTo(CoreKey::class, 'core_keys_id', 'core_keys_id');
    }

    public function core_keys()
    {
        return $this->hasMany(CoreKey::class, 'core_keys_id', 'core_keys_id');
    }

    public function purchased_count()
    {
        return $this->hasMany(PackageBoughtTransaction::class, 'package_id', 'id');
    }

    public function payment_attributes()
    {
        return $this->hasMany(PaymentAttribute::class, 'core_keys_id', 'core_keys_id');
    }

    public function daysAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'days');
    }

    public function typeAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'type');
    }

    public function countAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'count');
    }

    public function statusAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'status');
    }

    public function priceAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'price');
    }

    public function currencyIdAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'currency_id');
    }

    public function payment_info()
    {
        return $this->belongsTo(PaymentInfo::class, 'core_keys_id', 'core_keys_id');
    }

    public function offline_icon()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id')
            ->where('img_type', 'offline-payment-icon');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function salePriceAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'sale_price');
    }

    public function discountPriceAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'discount_price');
    }

    public function durationAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'duration');
    }

    public function isMostPopularPlanAttribute()
    {
        return $this->belongsTo(PaymentAttribute::class, 'core_keys_id', 'core_keys_id')->where('attribute_key', 'is_most_popular_plan');
    }
}
