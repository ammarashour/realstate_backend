<?php

namespace Modules\Installer\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserConfigurationController
{
    public function userConfiguration(){
        return view('vendor.installer.user-configuration');
    }

    public function userConfigurationUpdate(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        $user = User::first();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->route("LaravelInstaller::requirements");
    }
}
