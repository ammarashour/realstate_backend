<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorApprovalServiceInterface extends PsInterface
{
    public function setStatus($id, $vendorApprovalData);

    public function delete($id);

}