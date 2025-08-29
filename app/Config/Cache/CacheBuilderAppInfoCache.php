<?php

namespace App\Config\Cache;

class CacheBuilderAppInfoCache
{
    const BASE = "BUILDER_APP_INFO_CACHE";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
