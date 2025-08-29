<?php

namespace App\Http\Contracts\Notification;

use App\Http\Contracts\Core\PsInterface;

interface PushNotificationMessageServiceInterface extends PsInterface
{
    public function save($pushNotificationMessageData, $pushNotificationMessageImage = null);

    public function delete($id);

    public function get($id = null, $relation = null);

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $notIds=null,$noPagination = null,  $pagPerPage = null);
}
