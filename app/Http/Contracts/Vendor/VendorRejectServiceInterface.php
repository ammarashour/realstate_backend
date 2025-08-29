<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorRejectServiceInterface extends PsInterface
{
    public function setStatus($id, $vendorRejectData);

    public function delete($id);

}