<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorApplicationServiceInterface extends PsInterface
{
    public function save($vendorApplicationData, $vendorId);

    public function update ($id, $vendorApplicationData, $vendorId);

    public function get($id = null, $vendorId = null);

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null);

    public function delete($id);

    public function downloadDocument($applicationId = null, $vendorId = null);
}