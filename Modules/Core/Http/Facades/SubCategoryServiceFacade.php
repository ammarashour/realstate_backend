<?php

namespace Modules\Core\Http\Facades;

use Illuminate\Support\Facades\Facade;

class SubCategoryServiceFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ps_sub_category_service';
    }
}
