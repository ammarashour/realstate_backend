<?php

namespace App\Config\Cache;

class SubcategoryCache
{
    const BASE = "SUB_CATEGORY";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24 * 30;

    const GET_ALL_KEY = self::BASE."_GET";
    const GET_ALL_EXPIRY = 60 * 60 * 24 * 30;


}
