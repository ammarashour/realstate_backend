<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface BlueMarkUserServiceInterface extends PsInterface
{
    public function save($userInfoData);

    public function update($id, $userInfoData);

    public function delete($id);

    // public function get($id = null, $conds = null, $relation = null);

    public function getAll($conds=null, $noPagination = null, $pagPerPage = null);
}
