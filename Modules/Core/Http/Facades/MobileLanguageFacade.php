<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class MobileLanguageFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_mobile_language';
    }
}
