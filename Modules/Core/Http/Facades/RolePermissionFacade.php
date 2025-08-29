<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class RolePermissionFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_role_permission';
    }
}
