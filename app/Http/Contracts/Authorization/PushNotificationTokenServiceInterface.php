<?php

namespace App\Http\Contracts\Authorization;

use App\Http\Contracts\Core\PsInterface;

interface PushNotificationTokenServiceInterface extends PsInterface
{
    public function save($notiData, $loginUserId);

    public function update($id, $pushNotificationTokenData, $loginUserId);

    public function delete($id = null, $token = null);

    public function get($id = null, $conds = null, $relation = null, $deviceToken = null);

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null, $pagPerPage = null, $noPagination = null, $deviceToken = null);

    public function storeOrUpdateNotiToken($pushNotificationTokenData, $loginUserId);

    public function registerFromApi($pushNotificationTokenData, $langSymbol, $loginUserId);

    public function unregisterFromApi($pushNotificationTokenData, $langSymbol, $loginUserId);
}
