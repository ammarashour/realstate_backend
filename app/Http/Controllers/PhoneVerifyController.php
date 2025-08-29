<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Fortify;
use App\Models\User;

use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class PhoneVerifyController extends AuthenticatedSessionController
{

    // protected $userNotExistedAndNormalLogin;

    const parentPath = "Auth/";
    const indexPath = self::parentPath . "VerifyPhone";

    public function __construct()
    {

    }

    public function VerifyPhone()
    {
        return renderView(self::indexPath);

    }

    public function updateVerify(Request $request){

        dd($request->all());

    }

   
}
