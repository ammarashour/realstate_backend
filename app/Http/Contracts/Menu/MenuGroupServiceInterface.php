<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface MenuGroupServiceInterface extends PsInterface
{
    public function save($menuGroupData);

    public function update($id, $menuGroupData);

    public function delete($id);

    public function get($id, $relation = null, $conds = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $isHas = null, $ordering = null, $isShowOnMenu = null);

    public function setStatus($id, $status);
}
