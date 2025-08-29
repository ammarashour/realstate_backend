<?php

namespace App\Config\Cache;

class ActivatedFileNameCache
{
    const BASE = "ACTIVATED_FILE_NAME";

    const GET_KEY = self::BASE."_FIRST";
    const GET_EXPIRY = 60 * 60 * 24;

}
