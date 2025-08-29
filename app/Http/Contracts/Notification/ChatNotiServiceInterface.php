<?php

namespace App\Http\Contracts\Notification;

use App\Http\Contracts\Core\PsInterface;

interface ChatNotiServiceInterface extends PsInterface
{
    public function save($chatNotiData, $loginUserId);

    public function update($id, $chatNotiData, $loginUserId);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($conds = null, $relation = null, $limit = null, $offset = null, $pagPerPage = null, $noPagination = null);
}
