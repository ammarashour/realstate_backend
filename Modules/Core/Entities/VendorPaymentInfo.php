<?php

namespace Modules\Core\Entities;

use Modules\Core\Entities\Configuration\CoreKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPaymentInfo extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'payment_id', 'core_keys_id', 'vendor_id', 'value', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_vendor_payment_infos';

    const tableName = 'psx_vendor_payment_infos';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const paymentId = 'payment_id';
    const coreKeysId = 'core_keys_id';
    const vendorId = 'vendor_id';
    const value = 'value';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorPaymentInfoFactory::new();
    }

    public function core_key()
    {
        return $this->belongsTo(CoreKey::class, 'core_keys_id', 'core_keys_id');
    }
}
