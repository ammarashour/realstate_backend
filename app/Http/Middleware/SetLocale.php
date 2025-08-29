<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Modules\Core\Entities\Localization\Language;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if(Session::has('applocale')){
            App::setLocale(Session::get('applocale'));
        }

        return $next($request);
    }
}
