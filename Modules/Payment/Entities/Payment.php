<?php

namespace Modules\Payment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;
use Modules\Payment\Database\factories\PaymentFactory;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'description', 'status', 'added_date', 'added_uesr_id', 'updated_date', 'updated_user_id', 'updated_flag' ];

    protected $table = "psx_payments";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_payments";
    const id = "id";
    const name = "name";
    const description = "description";
    const status = "status";
    const addedDate = 'added_date';
    const addedUserId = "added_user_id";

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function newFactory()
    {
       return PaymentFactory::new();
    }

    public function payment_infos()
    {
        return $this->hasMany(PaymentInfo::class)->with('core_key');
    }

    public function payment_attributes()
    {
        return $this->hasMany(PaymentAttribute::class);
    }

    public function daysAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'days');
    }

    public function typeAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'type');
    }

    public function countAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'count');
    }

    public function statusAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'status');
    }

    public function priceAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'price');
    }

    public function currencyIdAttribute(){
        return $this->belongsTo(PaymentAttribute::class, 'id', 'payment_id')->where('attribute_key', 'currency_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
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
