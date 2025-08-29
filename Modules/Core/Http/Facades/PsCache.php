<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class PsCache extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_cache';
    }
}
