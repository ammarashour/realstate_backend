<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class SystemConfigFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_system_config';
    }
}
