<?php

namespace App\Http\Contracts\Menu;

use App\Http\Contracts\Core\PsInterface;

interface ModuleServiceInterface extends PsInterface
{
    public function save($moduleData);

    public function update($id, $moduleData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null, $subMenuId = null, $isNotUsedModules = null);

    public function getAll($relation = null, $pagPerPage = null, $conds = null, $status=null, $isNotUsedModules=null);

    public function setStatus($id, $status);
}
