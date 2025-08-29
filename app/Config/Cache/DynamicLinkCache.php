<?php

namespace App\Config\Cache;

class DynamicLinkCache
{
    const BASE = "DYNAMIC_LINK";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24 * 30;

    const GET_ALL_KEY = self::BASE."_GET";
    const GET_ALL_EXPIRY = 60 * 60 * 24 * 30;

}
