<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface BlockUserServiceInterface extends PsInterface
{
    public function save($userData);

    public function delete($userData);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null);
}
