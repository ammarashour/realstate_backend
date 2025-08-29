<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface CoreMenuServiceInterface extends PsInterface
{
    public function save($coreMenuData);

    public function update($id, $coreMenuData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $ids = null);

    public function setStatus($id, $status);
}
