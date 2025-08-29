<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class handleTimeout
{

    public function handle(Request $request, Closure $next)
    {
        $currentMaxExecutionTime = ini_get('max_execution_time');
        $current_domain = config("app.base_domain");
        $skipDomain = ["http://127.0.0.1:8000/", "http://localhost:8000/", "https://www.products.panacea-soft.co/"];
        if(!in_array($current_domain, $skipDomain)){
            if($currentMaxExecutionTime < 30000){
                return response()->view('errors.timeout', [], 500);
            }
        }

        return $next($request);
    }
}
