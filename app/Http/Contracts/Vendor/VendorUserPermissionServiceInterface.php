<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorUserPermissionServiceInterface extends PsInterface
{
    public function save($vendorUserPermissionData);
    
    public function get($userId, $relation = null);
}
