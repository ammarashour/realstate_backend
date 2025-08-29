<?php

namespace App\Http\Contracts\Vendor;

use App\Http\Contracts\Core\PsInterface;

interface VendorRoleServiceInterface extends PsInterface
{
    public function save($roleData);

    public function update($id, $roleData);

    public function get($id);

    public function getAll($relations = null, $conds = null, $noPagination = null, $pagPerPage = null, $sort = null, $roleIds = null, $status = null);

    public function delete($id);

    public function setStatus($id, $status);
}
