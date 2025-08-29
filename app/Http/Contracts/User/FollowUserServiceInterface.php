<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface FollowUserServiceInterface extends PsInterface
{
    public function save($followUserData);

    public function get($id = null, $conds = null);

    public function getAll($userId, $relation = null, $conds = null, $limit = null, $offset = null);
}
