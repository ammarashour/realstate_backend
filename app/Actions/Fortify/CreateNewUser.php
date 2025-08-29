<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Modules\Core\Entities\UserAccessApiToken;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255','unique:users,username'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $header_token   = $input['headerToken'];
        $deviceId = $input['deviceToken'];


        $userAccessApiToken = new UserAccessApiToken();
        $userAccessApiToken->device_info = 'Browser';
        $userAccessApiToken->device_id = $deviceId;
        $userAccessApiToken->device_token = $header_token;
        $userAccessApiToken->user_id = $user->id;
        $userAccessApiToken->save();
    

        return $user;

    }
}
