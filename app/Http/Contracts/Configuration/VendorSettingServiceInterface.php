<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface VendorSettingServiceInterface extends PsInterface
{
    public function update($id, $vendorSettingData);
}
