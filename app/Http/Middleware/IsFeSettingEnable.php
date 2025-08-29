<?php

namespace App\Http\Middleware;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Closure;
use Illuminate\Http\Request;

class IsFeSettingEnable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __construct(protected BackendSettingServiceInterface $backendSettingService)
    {

    }

    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $backendSetting = $this->backendSettingService->get();
        $feSetting = $backendSetting->fe_setting;
        if($feSetting == 0){
            return redirect()->route("dashboard");
        }
        else{
            return $next($request);
        }

    }
}
