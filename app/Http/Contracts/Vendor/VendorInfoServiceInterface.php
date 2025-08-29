<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorInfoServiceInterface extends PsInterface
{
    public function save($parentId, $customFieldValues);

    public function update($parentId, $customFieldValues);

    public function deleteAll($customFieldValues);

    public function getAll($coreKeysId = null, $vendorId = null, $relation = null, $noPagination = null, $pagPerPage = null);

    public function get($id = null, $relation = null);

}