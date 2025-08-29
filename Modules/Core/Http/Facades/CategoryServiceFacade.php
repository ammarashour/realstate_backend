<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class CategoryServiceFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_category_service';
    }
}
