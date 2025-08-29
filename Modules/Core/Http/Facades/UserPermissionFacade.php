<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class UserPermissionFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_user_permission';
    }
}
