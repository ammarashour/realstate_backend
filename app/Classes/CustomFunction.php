<?php

namespace App\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\UserManagement\Entities\Authorization;


class CustomFunction
{
    public static function permissionControl($module_id='',$permission_id=''){

        $loginUserId = Auth::id();
        // check request from api or backend
        if (!empty($_GET['login_user_id'])){
            $loginUserIdFromGet = $_GET['login_user_id'];
            $loginUserRoles = UserPermission::where('user_id',$loginUserIdFromGet)->first();
        } else {
            $loginUserRoles = UserPermission::where('user_id',$loginUserId)->first();
        }

        if (!empty($loginUserRoles)) {
            $roleIds = explode(',',$loginUserRoles->role_id);
            $userAccesses = RolePermission::whereIn('role_id',$roleIds)->where('module_id',$module_id)->get();

//        $userPermissions = UserPermission::where('user_id',$loginUserId)->where('module_id',$module_id)->first();
            foreach ($userAccesses as $userAccess){
                if ($userAccess){
                    $permission = $userAccess->permission_id;
                    $permissionIds = explode(',',$permission);
                    foreach ($permissionIds as $id){
                        if ($id == $permission_id){
                            return true;
                        }
                    }

                }
            }
        }

    }


    public static function authorization($module_id){

        //if Admin
//        if (Auth::user()->role_id == 1){
//            return true;
//        }

        $loginUserRoles = UserPermission::where('user_id',Auth::id())->first();
        if ($loginUserRoles){
            $roleIds = explode(',',$loginUserRoles->role_id);
            $userAccesses = RolePermission::whereIn('role_id',$roleIds)->where('module_id',$module_id)->get();
            if ($userAccesses->isNotEmpty()){
                return true;
            } else{
                return false;
            }
        }

//        $result = Authorization::where('user_id',Auth::id())->where('module_id',$module_id)->first();
    }

}
