<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class FrontendSettingFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_frontend_setting';
    }
}
