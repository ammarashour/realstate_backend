<?php

namespace App\Http\Contracts\Notification;

use App\Http\Contracts\Core\PsInterface;

interface ChatHistoryServiceInterface extends PsInterface
{

    public function save($chatHistoryData, $loginUserId);

    public function update($id, $chatHistoryData, $loginUserId);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relation = null, $limit = null, $offset = null, $conds = null, $in_conds = null, $condsNotIn = null, $pagPerPage = null, $noPagination = null);
}
