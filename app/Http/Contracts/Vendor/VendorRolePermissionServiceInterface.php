<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorRolePermissionServiceInterface extends PsInterface
{
    public function save($VendorRolePermissionData);

    public function update($roleId, $VendorRolePermissionData);

    public function getAll($id);

    public function deleteAll($id);
}