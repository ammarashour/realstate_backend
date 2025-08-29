<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorBranchServiceInterface extends PsInterface
{
    public function save($vendorBranchData);

    public function update($id, $vendorBranchData);

    public function get($id, $relation = null);

    public function getAll($limit = null, $offset = null, $noPagination = null,  $pagPerPage = null, $conds = null);

    public function delete($id);

    public function deleteAll($vendorId, $excludedBranchIds);
}