<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class LanguageFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_language';
    }
}
