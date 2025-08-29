<?php

namespace App\Config\Cache;

class CheckVersionUpdateCache
{
    const BASE = "CHECK_VERSION_UPDATE";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
