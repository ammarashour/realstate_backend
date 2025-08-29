<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Validator;
// use App\Http\Requests\LoginRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Auth;
use Carbon\Carbon;
use Laravel\Jetstream\Jetstream;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\Core\Entities\UserAccessApiToken;
use Illuminate\Validation\ValidationException;
use DB;



use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\Configuration\ColorService;
use App\Http\Controllers\CustomLoginController;
use Modules\Core\Http\Facades\BackendSettingFacade;
use Modules\Core\Http\Services\Authorization\PushNotificationTokenService;
use stdClass;

class JetstreamServiceProvider extends ServiceProvider
{
    protected $pushNotificationTokenService, $userApiController, $publish, $normalUserRoleId, $googleVerify, $badRequestStatusCode, $userService, $imageService;

    public function __construct()
    {


        $this->googleVerify = Constants::googleVerify;

        $this->publish = Constants::publishUser;

        $this->normalUserRoleId = Constants::normalUserRoleId;

        $this->badRequestStatusCode = Constants::badRequestStatusCode;

        $this->pushNotificationTokenService = app(PushNotificationTokenService::class);


        // $this->userService = $userService;

        // $this->imageService = $imageService;
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {

        // Fortify::loginView(function () {
        //     if (Route::get('/login')) {
        //         return Inertia::render('');
        //     }

        //     // return view('frontend.auth.login');
        // });

        // $folder_path=(new ImageService($colorService))->getFolderImagePath();


        Fortify::authenticateUsing(function ($request) {

            // dd($request->all());
            if ($request->loginMethod == 'apple') {
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $appleLogin = (new CustomLoginController($imageService));
                $user = $appleLogin->appleRegister($request);
                // dd($user);

                if ($user['error']) {
                    throw ValidationException::withMessages([
                        'user_ban' => $user['error'],
                    ]);
                }
                session(["loginUserId"=>$user->id]);
                return $user;
            }
            if ($request->loginMethod == 'google') {
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $googleLogin = (new CustomLoginController($imageService));
                $user = $googleLogin->googleLoginService($request);

                if ($user['error']) {
                    throw ValidationException::withMessages([
                        'user_ban' => $user['error'],
                    ]);
                }
                session(["loginUserId"=>$user->id]);
                return $user;
            }
            if ($request->loginMethod == 'facebook') {
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $facebooklogin = (new CustomLoginController($imageService));
                $user = $facebooklogin->facebookRegister($request);



                if ($user['error']) {
                    throw ValidationException::withMessages([
                        'user_ban' => $user['error'],
                    ]);
                }
                session(["loginUserId"=>$user->id]);
                return $user;
            } elseif ($request->loginMethod == 'phone') {



                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $phoneLogin = (new CustomLoginController($imageService));
                $user = $phoneLogin->phoneRegister($request);

                // dd($user['status']);
                if ($user['error']) {
                    throw ValidationException::withMessages([
                        'user_ban' => $user['error'],
                    ]);
                }
                session(["loginUserId"=>$user->id]);
                return $user;
            } else {




                $string = preg_replace('/\s+/', '', $request->email);;
                $pattern = '/^\+\d/';
                $loginType = '';
                $userPhone = [];

                if (preg_match($pattern, $string,$matchesPhone)) {
                    $firstDigit=$matchesPhone[0];
                    // dd($firstDigit);
                    $loginType = 'phone';
                    $patternForPhone = '/\d{5}$/';

                    if (preg_match($patternForPhone, $string, $matches)) {
                        $lastFiveDigits = $matches[0];
                        // dd($lastFiveDigits);
                    } else {
                        throw ValidationException::withMessages([
                            'user_is_bannded' => "invalid login",
                        ]);
                    }


                    $userId='';
                    $userList = User::whereRaw("REPLACE(user_phone, '-', '') LIKE ?", ["%{$firstDigit}%"])->whereRaw("REPLACE(user_phone, '-', '') REGEXP ?", [$lastFiveDigits])->get();
                    // dd($userList);
                    foreach ($userList as $key => $user) {
                        $userPhone[$user->id] = str_replace("-", "", "$user->user_phone");
                        // $userPhone['value'] =  str_replace("-", "", "$user->user_phone");
                        // $userPhone['value'] = str_replace("-", "", "$user->user_phone");
                        $userPhone += [$user->id => str_replace("-", "", "$user->user_phone")];
                        if ($userPhone[$user->id] == $string) {
                            $userId = $user->id;
                        }
                    }

                    // dd($userId);
                } else {
                    $loginType = 'stringType';
                }



                if ($loginType == 'phone') {

                    if(!empty($userList) ){
                        if($userId != ''){
                            $user = User::Where('id', $userId)->first();
                        }
                        else{
                            throw ValidationException::withMessages([
                                'user_is_bannded' => "invalid login",
                            ]);
                        }
                    }else{
                        throw ValidationException::withMessages([
                            'user_is_bannded' => "invalid login",
                        ]);
                    }

                } else {
                    $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
                }
                // dd($user);

                if (
                    $user &&
                    Hash::check($request->password, $user->password)
                ) {

                    if ($user->is_banned == 1) {
                        throw ValidationException::withMessages([
                            'user_is_bannded' => __("core__api_banned_user"),
                        ]);
                    }

                    $backendSetting = BackendSettingFacade::get();
                    if ($backendSetting->email_verification_enabled == 1 && isset($user->code) && $user->code != '' && $user->code != null) {
                        throw ValidationException::withMessages([
                            'user_need_verify' => "1",
                        ]);
                    }

                    if ($user->status != 1) {
                        throw ValidationException::withMessages([
                            'user_status' => __("core__api_unpublished_user"),
                        ]);
                    }

                    $header_token   = $request->headerToken;
                    $deviceId = $request->deviceToken;

                    $userAccessApiToken = UserAccessApiToken::where('user_id', $user->id)
                        ->where('device_id', $deviceId)
                        ->where('device_token', $header_token)
                        ->first();

                    if (!empty($userAccessApiToken)) {
                        $userAccessApiToken->device_info = 'Browsers';
                        $userAccessApiToken->device_id = $deviceId;
                        $userAccessApiToken->device_token = $header_token;
                        $userAccessApiToken->user_id = $user->id;
                        $userAccessApiToken->update();
                    } else {
                        // save in user_access_api_tokens table
                        $userAccessApiToken = new UserAccessApiToken();
                        $userAccessApiToken->device_info = 'Browsers';
                        $userAccessApiToken->device_id = $deviceId;
                        $userAccessApiToken->device_token = $header_token;
                        $userAccessApiToken->user_id = $user->id;
                        $userAccessApiToken->save();
                    }

                    $pushNotificationTokenData = new stdClass();
                    $pushNotificationTokenData->device_token = $deviceId;
                    $pushNotificationTokenData->platform_name = 'frontend';
                    $pushNotificationTokenData->user_id = $user->id;
                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$pushNotificationTokenData, $user->id);
                    session(["loginUserId"=>$user->id]);

                    return $user;
                }
            }
        });
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    protected function configurePermissions()
    {


        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            "adminMobileToken",
            "userMobileToken",
            "userWebsiteToken",
            "deliboyMobileToken",
            "builderToken",
        ]);
    }
}
