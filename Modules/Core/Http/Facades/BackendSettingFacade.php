<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class BackendSettingFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_backend_setting';
    }
}
