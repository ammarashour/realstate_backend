<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Vendor\VendorUserPermission;
use Modules\Core\Entities\Vendor\VendorRole;

class VendorAcces
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
        try {
            $loginUserId = Auth::id();
            $vendorId = getVendorIdFromSession();
            if(!$vendorId || !isVendorSettingOn() || !isVendorEnable($vendorId)){
                return abort(404);
            }
            
            $vendorRole = VendorUserPermission::where('user_id',$loginUserId)->first();
            if(!$vendorRole){
                return abort(404);
            }
            $vendorRoleObj = json_decode($vendorRole->vendor_and_role);
            if(!isset($vendorRoleObj->$vendorId)){
                return abort(404);
            }else{
                //check if role is publish
                $getRoleIds = explode(',', $vendorRoleObj->$vendorId);
                
                $roleIds = VendorRole::whereIn('id', $getRoleIds)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();

                if(count($roleIds) == 0){
                    return abort(404);
                }
            }
        } catch (\Throwable $e) {
            return abort(404);
        }
        

        return $next($request);
    }
}
