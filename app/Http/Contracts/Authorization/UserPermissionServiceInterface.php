<?php

namespace App\Http\Contracts\Authorization;

use App\Config\ps_constant;
use App\Http\Contracts\Core\PsInterface;

interface UserPermissionServiceInterface extends PsInterface
{
    public function save($userPermissionData);

    public function update($id = null, $userId = null, $userPermissionData);

    public function delete($id = null, $userId = null);

    public function get($userId = null, $roleId = null);

    public function getAll($userId = null, $roleId = null, $pagPerPage = null, $noPagination = null);
}
