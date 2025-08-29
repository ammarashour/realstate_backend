<?php

namespace App\Config\Cache;

class UserPermissionCache
{
    const BASE = "USER_PERMISSION";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24 * 30;

}
