<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface PushNotificationUserServiceInterface extends PsInterface
{
    public function save($pushNotificationUserData, $loginUserId);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll($userId = null, $isSoftDel = null, $noPagination = null, $pagPerPage = null, $conds = null);
}
