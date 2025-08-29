<?php

namespace App\Config\Cache;

class MbSettingCache
{
    const BASE = "MB_SETTING";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
