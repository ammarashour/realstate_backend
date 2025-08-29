<?php

namespace Modules\Core\Entities\Vendor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorApplication extends Model
{
    use HasFactory;

    protected $fillable = ['id','vendor_id','user_id','document','cover_letter','added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_vendor_applications';

    const tableName = 'psx_vendor_applications';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const vendorId = 'vendor_id';
    const userId = 'user_id';
    const document = 'document';
    const coverLetter = 'cover_letter';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorApplicationFactory::new();
    }
}
