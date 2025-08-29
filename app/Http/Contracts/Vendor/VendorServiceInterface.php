<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorServiceInterface extends PsInterface
{
    public function setSession($id = null);

    public function get($id, $relation = null);

    public function getAll($ownerId = null, $status = null, $relation = null, $pagPerPage = null, $conds = null, $limit = null, $offset = null, $ids = null);

    public function delete($id);

    public function update($id, $vendorData);

    public function save($vendorData);

    public function isUnlimitedChange($id, $isUnlimited);
}
