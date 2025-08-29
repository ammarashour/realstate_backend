<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface PushNotificationReadUserServiceInterface extends PsInterface
{
    public function isReadFromApi($pushNotiReadUserData, $loginUserId, $deviceToken, $langSymbol);

    public function isUnreadFromApi($pushNotiReadUserData, $loginUserId, $headerToken, $langSymbol);

    public function destroyFromApi($pushNotiReadUserData, $loginUserId, $headerToken, $langSymbol);
}
