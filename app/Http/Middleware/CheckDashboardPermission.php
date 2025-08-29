<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDashboardPermission
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
        // Testing pieces
        // return $next($request);
        // return response()->json([
        //     'status' => 'error',
        //     'message' => $rolePermission
        // ], 404);


        // Logic before using with global function
        // if (Auth::check()){
        //     if(Auth::user()->can_access_admin_panel){
        //         $authUserId = Auth::id();
        //         $user = User::with(['user_permissions', 'role_permissions'])->where('id', $authUserId)->first();
        //         $userPermission = $user->user_permissions;
        //         $rolePermission = $user->role_permissions;

        //         if ($rolePermission->isEmpty()) {
        //             return redirect()->route("dashboard");
        //         } else {
        //             return $next($request);
        //         }
        //     } else {
        //         return redirect()->route("dashboard");
        //     }
        // }

        if(checkForDashboardPermission()){
            return $next($request);
        } else {
            return redirect()->route("dashboard");
        }

    }
}
