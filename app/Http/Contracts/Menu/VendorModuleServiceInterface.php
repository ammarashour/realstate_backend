<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface VendorModuleServiceInterface extends PsInterface
{
    public function save($vendorModuleData);

    public function update($id, $vendorModuleData);

    public function delete($id);

    public function get($id = null, $subMenuId = null, $isNotUsedModules = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $status = null, $isNotUsedModules = null, $ids = null, $isNotEmptySubMenuId = null, $isNotEmptyMenuId = null);

    public function setStatus($id, $status);
}
