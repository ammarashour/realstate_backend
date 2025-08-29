<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface VendorMenuServiceInterface extends PsInterface
{
    public function save($vendorMenuData);

    public function update($id, $vendorMenuData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isShowOnMenu = null, $ids = null, $ordering = null);

    public function setStatus($id, $status);
}
