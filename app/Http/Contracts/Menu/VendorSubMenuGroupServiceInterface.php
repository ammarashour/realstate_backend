<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface VendorSubMenuGroupServiceInterface extends PsInterface
{
    public function save($vendorSubMenuGroupData);

    public function update($id, $vendorSubMenuGroupData);

    public function delete($id);

    public function get($id = null, $relation = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isDropdown = null, $ids = null, $isShowOnMenu = null, $ordering = null);

    public function setStatus($id, $status);
}
