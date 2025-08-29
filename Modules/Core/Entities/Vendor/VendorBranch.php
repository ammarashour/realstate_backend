<?php

namespace Modules\Core\Entities\Vendor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorBranch extends Model
{
    use HasFactory;

    protected $fillable = ['id','vendor_id','name','email','phone','address','description','added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_vendor_branches';

    const tableName = 'psx_vendor_branches';

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const vendorId = 'vendor_id';
    const name = 'name';
    const email = 'email';
    const phone = 'phone';
    const address = 'address';
    const description = 'description';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';
    const updatedFlag = 'updated_flag';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\VendorBranchFactory::new();
    }
}
