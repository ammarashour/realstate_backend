<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Core\Entities\UpdaterData;

/**
 * @deprecated
 */
class HandleUpdaterData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(config("app.development")){
            return $next($request);
        } else {
            $updaterData = UpdaterData::first();
            if (!empty($updaterData)){
                $currentRouteName = Route::currentRouteName();
                $routeNames = ["table.index", "table.import.zip", "table.field.override", "table.field.handleProjectNotSame"];
                if (in_array($currentRouteName, $routeNames)){
                    return $next($request);
                } else {
                    return Inertia::render('ToTableModule');
                }
            } else {
                return $next($request);
            }
        }

    }
}
