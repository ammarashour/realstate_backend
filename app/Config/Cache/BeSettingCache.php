<?php

namespace App\Config\Cache;

class BeSettingCache
{
    const BASE = "BE_SETTING";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
