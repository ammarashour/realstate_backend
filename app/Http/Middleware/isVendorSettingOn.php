<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;

class isVendorSettingOn
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
        $backendSetting = $this->backendSettingService->get();
        $venodrSetting = $backendSetting->vendor_setting;
        if($venodrSetting == 0){
            return abort(404);
        }
        else{
            return $next($request);
        }

    }
}
