<?php

namespace App\Config\Cache;

class SystemConfigCache
{
    const BASE = "SYSTEM_CONFIG";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
