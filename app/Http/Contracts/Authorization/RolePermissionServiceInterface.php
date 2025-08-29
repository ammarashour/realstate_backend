<?php

namespace App\Http\Contracts\Authorization;

use App\Config\ps_constant;
use App\Http\Contracts\Core\PsInterface;

interface RolePermissionServiceInterface extends PsInterface
{
    public function save($rolePermissionData);

    public function deleteAll($ids);

    public function getAll($roleIds = null, $moduleId = null, $pagPerPage = null, $noPagination = null, $roleId = null);
}
