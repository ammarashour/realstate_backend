<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface SubMenuGroupServiceInterface extends PsInterface
{
    public function save($subMenuGroupData);

    public function update($id, $subMenuGroupData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $whereNullData = null, $ordering = null);

    public function setStatus($id, $status);
}
