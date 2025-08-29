<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class MobileSettingFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_mobile_setting';
    }
}
