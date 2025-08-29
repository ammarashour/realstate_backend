<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class HandleInertiaRequestFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_handle_inertia_request';
    }
}
