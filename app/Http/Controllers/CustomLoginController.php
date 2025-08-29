<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Fortify;
use App\Models\User;

use Illuminate\Http\Request;
use Modules\Core\Entities\UserAccessApiToken;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Modules\Core\Entities\Authorization\UserPermission;

use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;

use Laravel\Fortify\Actions\AttemptToAuthenticate;
use App\Handlers\Admin\AuthHandler;

use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Rules\SepcialCharacter;

use Modules\Core\Transformers\Api\App\V1_0\User\CheckUserApiResource;
use Modules\Core\Entities\Configuration\PhoneCountryCode;




use Illuminate\Routing\Pipeline;

use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use App\Actions\Fortify\PSAttemptToAuthenticate;
use Illuminate\Support\Facades\Validator;

use App\Config\ps_constant;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use Modules\Core\Http\Services\Authorization\PushNotificationTokenService;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\Configuration\ColorService;
use Modules\Core\Http\Services\Financial\ItemCurrencyService;




/**
 * @todo do we still need ?
 */
class CustomLoginController extends AuthenticatedSessionController
{

    protected $userService, $backendSettingService, $appleVerify, $phoneVerify, $facebookVerify, $userApiController, $publish, $normalUserRoleId, $googleVerify, $badRequestStatusCode, $imageService, $successStatus, $okStatusCode, $userExisted, $userNotExisted, $errorStatus, $userNotExistedAndNormalLogin;

    const parentPath = "Auth/";
    const indexPath = self::parentPath . "CustomLogin";
    const phoneLoginPath = self::parentPath . "PhoneLogin";
    const verifyCodePath = self::parentPath . "VerifyCode";
    const verifyEmailPath = self::parentPath . "UserVerifyEmail";
    const resetPasswordPath = self::parentPath . "ResetPassword";


    public function __construct(ImageService $imageService)
    {

        $this->userService = app(UserServiceInterface::class);

        $this->googleVerify = Constants::googleVerify;

        $this->publish = Constants::publishUser;

        $this->normalUserRoleId = Constants::normalUserRoleId;

        $this->badRequestStatusCode = Constants::badRequestStatusCode;

        $this->successStatus = Constants::successStatus;

        $this->okStatusCode = Constants::okStatusCode;

        $this->errorStatus = Constants::errorStatus;

        $this->userExisted = "1";

        $this->userNotExisted = "2";

        $this->userNotExistedAndNormalLogin = "3";

        $this->imageService = $imageService;

        $this->phoneVerify = Constants::phoneVerify;

        $this->facebookVerify = Constants::facebookVerify;

        $this->appleVerify = Constants::appleVerify;

        $this->backendSettingService = app()->make(BackendSettingServiceInterface::class);
    }


    // const indexRoute = "category.index";
    // const createRoute = "category.create";


    public function existUser(Request $request)
    {
        // dd($request->all());
        if ($request->loginMethod == 'checkVerifyEmail') {
            $user = User::where('email', $request->email)->orWhere('username', $request->email)->orwhere('user_phone', $request->email)->first();
        }
        if ($request->loginMethod == 'checkFromDashboard') {
            $user = User::where('id', $request->id)->first();
        }
        if ($request->loginMethod == 'normal' || $request->loginMethod == 'google') {
            $user = User::where('email', $request->email)
                ->orWhere('user_phone', $request->email)
                ->when($request->google_id, function ($q) use ($request) {
                    $q->orwhere('google_id', $request->google_id);
                })
                ->first();
        }
        if ($request->loginMethod == 'facebook') {
            $validator = Validator::make($request->all(), [
                // 'username' => 'required|username_rule|string|unique:users',
                'facebook_id' => 'required|string',
            ]);
            if ($validator->fails()) {

                $errors = $validator->getMessageBag()->getMessages();
                return responseMsgApi($errors, $this->okStatusCode, $this->errorStatus);
            }
            $user = User::where('email', $request->email)->orWhere('facebook_id', $request->facebook_id)->first();
        }
        if ($request->loginMethod == 'apple') {
            $validator = Validator::make($request->all(), [
                // 'username' => 'required|username_rule|string|unique:users',
                'apple_id' => 'required|string',
            ]);
            if ($validator->fails()) {

                $errors = $validator->getMessageBag()->getMessages();
                return responseMsgApi($errors, $this->okStatusCode, $this->errorStatus);
            }
            $user = User::where('email', $request->email)->orWhere('apple_id', $request->apple_id)->first();
        }
        if ($request->loginMethod == 'phone') {
            // dd($request->all());
            $user = User::where('user_phone', $request->user_phone)->first();
            if (empty($user)) {

                $requestData['username'] = $request->user_name;
                $requestData['user_phone'] = $request->user_phone;
                $requestData['display_name'] = $request->user_phone;
                Validator::extend('username_rule', function ($attr, $value) {
                    return preg_match('/^[a-zA-Z0-9]+$/', $value);
                });

                $validator = Validator::make($requestData, [
                    // 'username' => 'required|username_rule|string|unique:users',
                    'user_phone' => 'required|string',
                    'display_name' => 'required'
                ]);
                if ($validator->fails()) {

                    $errors = $validator->getMessageBag()->getMessages();
                    return responseMsgApi($errors, $this->okStatusCode, $this->errorStatus);
                }
            }
        }

        $data['user_isexisted'] = $this->userNotExisted;
        $data['hasPassword'] = 'false';
        if (!empty($user)) {

            $data['hasPassword'] = $user->password ? 'true' : 'false';
        }

        $user = new CheckUserApiResource($user);
        $data['user'] = $user;
        // dd($user);




        //response api
        if ($request->loginMethod == 'normal') {

            if ($user) {
                if (empty($user->username)) {
                    return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
                }
                $data['user_isexisted'] = $this->userExisted;
                return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
            }
            $data['user_isexisted'] = $this->userNotExisted;
            return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
        } elseif ($request->loginMethod == 'checkFromDashboard') {
            if ($user) {
                if (empty($user->username)) {
                    return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
                }
                $data['user_isexisted'] = $this->userExisted;
                return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
            }
            $data['user_isexisted'] = $this->userExisted;
            return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
        } elseif ($request->loginMethod == 'checkVerifyEmail') {
            $data['user_isexisted'] = $this->userExisted;
            return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
        } else {
            if ($user) {
                if (empty($user->username)) {
                    return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
                }
                $data['user_isexisted'] = $this->userExisted;
                return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
            }
            return responseMsgApi($data, $this->okStatusCode, $this->successStatus);
        }
    }

    public function phoneLogin(Request $request)
    {



        $dataArr['phoneCodes'] = PhoneCountryCode::get();
        // dd($dataArr['phoneCodes']);

        $dataArr['userLoginData'] = $request->all();

        return renderView(self::phoneLoginPath, $dataArr);
    }


    public function resetPassword($id, $code)
    {

        $dataArr['id'] = $id;
        $dataArr['code'] = $code;

        return renderView(self::resetPasswordPath, $dataArr);
    }

    public function verifyForgotPasswordCode($email)
    {
        $dataArr['email'] = $email;
        return renderView(self::verifyCodePath, $dataArr);
    }

    public function userVerifyEmail(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $dataArr['flag'] = 'error';
            $dataArr['error_validation'] = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));
            return renderView(self::verifyEmailPath, $dataArr);
        }
        /// validation end
        $dataArr['flag'] = 'success';
        $dataArr['email'] = $request->email;
        $dataArr['user_id'] = $request->user_id;
        $dataArr['password'] = $request->password;
        return renderView(self::verifyEmailPath, $dataArr);
    }

    public function googleLoginService($request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',

        ])->validate();

        /// validation end

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = $request->email;
        $user_data->google_id = $request->google_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->googleVerify;
        $user_data->code = '';
        $added_date = Carbon::now();

        $conds['google_id'] = $request->google_id;

        if (!$this->exists($conds)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }

            $google_id = $request->google_id;
            $url = $request->profile_photo_url;

            if ($url != "") {
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));

                $img = md5(time()) . '.jpg';
                $imageService->saveImageFromUrl($img, $url);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $request->name;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);
            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->getUser(null, $cond_user_existed);

                $id = empty($user_infos) ? '' : $user_infos->id;
            }

            if ($id != "") {
                //user email alerady exist
                $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $cond_user_existed);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $user_infos->email;
                } else if ($name == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $email;
                } else if ($email == "") {
                    $user_data->name = $name;
                    $user_data->email = $user_infos->email;
                } else {
                    $user_data->name = $name;
                    $user_data->email = $email;
                }

                if ($id != '') {
                    $user_data->id = $id;
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->updateFromApi($user_data, $id);
                } else {
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->storeFromApi($user_data);
                }

                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                if ($request->password && $request->registerForm == 1) {
                    $user_data->password = $request->password;
                    $user_data->username = $request->username;
                }
                $user = $this->storeFromApi($user_data);
                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                // $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            // $user = User::where($conds)->first();
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['google_id'] = $request->google_id;
            $backendSetting = $this->backendSettingService->get();
            $user_social_info_override = $backendSetting->user_social_info_override;
            // $user_social_info_override = $this->backendSettingService->getBackendSetting()->user_social_info_override;

            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $imageService->deleteImage($user_cover_photo);

                //Download again
                $google_id = $request->google_id;
                $url = $request->profile_photo_url;

                if ($url != "") {

                    $colorService = (new ColorService);
                    $imageService = (new ImageService($colorService));
                    $img = md5(time()) . '.jpg';
                    $imageService->saveImageFromUrl($img, $url);
                    $user_data->user_cover_photo = $img;
                }

                $user_data->name = $request->name;
                $user_data->added_date = $added_date;
                $user_data->added_date_timestamp = strtotime($added_date);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($name == "") {
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($email == "") {
                    $user_data->name = $request->name;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else {
                    $user_data->name = $request->name;
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    // $user_data->user_cover_photo = $request->user_cover_photo;
                }

                $conds['google_id'] = $request->google_id;
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {
                    return ['error' => __('core__api_banned_user', [], $request->language_symbol)];


                    // return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return ['error' => __('core__api_unpublished_user', [], $request->language_symbol)];
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                    // $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {
                    return ['error' => __('core__api_banned_user', [], $request->language_symbol)];
                    // return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return ['error' => __('core__api_unpublished_user', [], $request->language_symbol)];
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                    // $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        // $userId = $user->id;
        // $deviceId = $request->device_id;
        // $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
        // if (!$userAccessApiTokenStoreOrUpdate) {
        //     return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        // }
        // save or update in user_access_api_tokens table end

        // $user = new UserApiResource($user);






        if ($user) {

            $header_token   = $request->headerToken;
            $deviceId = $request->deviceToken;

            $userAccessApiToken = UserAccessApiToken::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->where('device_token', $header_token)
                ->first();

            if (!empty($userAccessApiToken)) {
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->update();
            } else {
                // save in user_access_api_tokens table
                $userAccessApiToken = new UserAccessApiToken();
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->save();
            }

            return $user;
        }


        // else{

        // }

    }
    function facebookRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'facebook_id' => 'required',
        ]);

        // if($request->language_symbol){
        //     $this->translator->setLocale($request->language_symbol);
        //     $validator->setTranslator($this->translator);
        // }

        // if ($validator->fails()) {
        //     return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        // }
        /// validation end

        $backendSetting = $this->backendSettingService->get();
        $app_token = $backendSetting->app_token;

        // $app_token = $this->backendSettingService->getBackendSetting()->app_token;

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = $request->email;
        $user_data->facebook_id = $request->facebook_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->facebookVerify;
        $user_data->code = '';
        if ($request->password && $request->registerForm == 1) {
            $user_data->password = $request->password;
            $user_data->username = $request->username;
        }
        $added_date = Carbon::now();
        // dd($request->facebook_id);

        //Need to check facebook_id is aleady exist or not?
        $conds['facebook_id'] = $request->facebook_id;
        if (!$this->exists($conds)) {
            // dd('notexist');
            //User not yet exist

            //prepare validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // if($request->language_symbol){
            //     $this->translator->setLocale($request->language_symbol);
            //     $validator->setTranslator($this->translator);
            // }

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $facebook_id = $request->facebook_id;

            if ($request->profile_photo_url != "") {
                // $url = "https://graph.facebook.com/$request->profile_img_id/picture?width=350&height=500&access_token=" . $app_token;
                $url = $request->profile_photo_url . '?width=350&height=500&access_token=' . $app_token;
                // dd($url);
                $img = md5(time()) . '.jpg';
                // dd("profile = " + $url);
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $imageService->saveImageFromUrl($img, $url);
                // dd($img);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $request->name;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);

            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->getUser(null, $cond_user_existed);
                $id = empty($user_infos) ? '' : $user_infos->id;
            }

            if ($id != "") {
                //user email alerady exist
                $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $cond_user_existed);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $user_infos->email;
                } else if ($name == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $email;
                } else if ($email == "") {
                    $user_data->name = $name;
                    $user_data->email = $user_infos->email;
                } else {
                    $user_data->name = $name;
                    $user_data->email = $email;
                }

                if ($id != '') {
                    $user_data->id = $id;
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->updateFromApi($user_data, $id);
                } else {
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->storeFromApi($user_data);
                }

                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));

                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                if ($request->password && $request->registerForm == 1) {
                    $user_data->password = $request->password;
                    $user_data->username = $request->username;
                }
                $user = $this->storeFromApi($user_data);
                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            // dd('already exist');
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['facebook_id'] = $request->facebook_id;
            $user_social_info_override = $backendSetting->user_social_info_override;
            // $user_social_info_override = $this->backendSettingService->getBackendSetting()->user_social_info_override;

            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $imageService->deleteImage($user_cover_photo);

                //Download again
                $facebook_id = $request->facebook_id;
                if ($request->profile_photo_url != "") {
                    // $url = "https://graph.facebook.com/712672440299716/picture?width=350&height=500&access_token=761389362219506|pJLYS144EHFR_SJEa6m1GNHtT1E" . $app_token;

                    $url = $request->profile_photo_url . '?width=350&height=500&access_token=' . $app_token;
                    // dd($url);
                    $img = md5(time()) . '.jpg';
                    $imageService->saveImageFromUrl($img, $url);
                    $user_data->user_cover_photo = $img;
                    // dd($user_data->user_cover_photo);
                }

                $user_data->name = $request->name;
                $user_data->added_date = $added_date;
                $user_data->added_date_timestamp = strtotime($added_date);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($name == "") {
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($email == "") {
                    $user_data->name = $request->name;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else {
                    $user_data->name = $request->name;
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    // $user_data->user_cover_photo = $request->user_cover_photo;
                }

                $conds['facebook_id'] = $request->facebook_id;
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }
                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));

                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));

                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        if ($user) {

            $header_token   = $request->headerToken;
            $deviceId = $request->deviceToken;

            $userAccessApiToken = UserAccessApiToken::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->where('device_token', $header_token)
                ->first();

            if (!empty($userAccessApiToken)) {
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->update();
            } else {
                // save in user_access_api_tokens table
                $userAccessApiToken = new UserAccessApiToken();
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->save();
            }

            return $user;
        }
    }

    function phoneRegister(Request $request)
    {
        // dd($request->all());
        //prepare validation
        $validator = Validator::make($request->all(), [
            'phone_id' => 'required',
            'user_phone' => 'required',
        ]);

        // if($request->language_symbol){
        //     $this->translator->setLocale($request->language_symbol);
        //     $validator->setTranslator($this->translator);
        // }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = "";
        $user_data->user_phone = $request->user_phone;
        $user_data->phone_id = $request->phone_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->phoneVerify;
        $user_data->code = '';
        $added_date = Carbon::now();

        //Need to check phone_id is aleady exist or not?
        $conds['phone_id'] = $request->phone_id;
        if (!$this->exists($conds)) {
            //User not yet exist

            //prepare validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // if($request->language_symbol){
            //     $this->translator->setLocale($request->language_symbol);
            //     $validator->setTranslator($this->translator);
            // }

            // if ($validator->fails()) {
            //     return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            // }
            /// validation end
            $phone_id = $request->phone_id;

            if ($user_data->user_phone != "") {
                $cond_user_existed['user_phone'] = $user_data->user_phone;
                $user_infos = $this->getUser(null, $cond_user_existed);
                $id = empty($user_infos) ? '' : $user_infos->id;
            }

            if ($id != "") {
                //user phone alerady exist
                $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $cond_user_existed);

                //for user name and user phone
                $name = $request->name;
                $user_phone = $request->user_phone;

                if ($name == "" && $user_phone == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->user_phone = $user_infos->user_phone;
                } else if ($name == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->user_phone = $user_phone;
                } else if ($user_phone == "") {
                    $user_data->name = $name;
                    $user_data->user_phone = $user_infos->user_phone;
                } else {
                    $user_data->name = $name;
                    $user_data->user_phone = $user_phone;
                }

                if ($id != '') {
                    $user_data->id = $id;
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->updateFromApi($user_data, $id);
                } else {
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->storeFromApi($user_data);
                }

                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));

                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                $userAccessApiToken = (new UserAccessApiTokenService);
                $currencyService = (new ItemCurrencyService);
                $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));
                //user phone not exist
                if ($request->password && $request->registerForm == 1) {
                    $user_data->password = $request->password;
                    $user_data->username = $request->username;
                }
                $user = $this->storeFromApi($user_data);
                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['phone_id'] = $request->phone_id;
            $backendSetting = $this->backendSettingService->get();
            $user_social_info_override = $backendSetting->user_social_info_override;
            // $user_social_info_override = $this->backendSettingService->getBackendSetting()->user_social_info_override;

            if ($user_social_info_override == '1') {

                //Download again
                $phone_id = $request->phone_id;

                //for user name and user phone
                $name = $request->name;
                $user_phone = $request->user_phone;

                if ($name == "" && $user_phone == "") {
                    $user_data->device_token = $request->device_token;
                } else if ($name == "") {
                    $user_data->user_phone = $request->user_phone;
                    $user_data->device_token = $request->device_token;
                } else if ($user_phone == "") {
                    $user_data->name = $request->name;
                    $user_data->device_token = $request->device_token;
                } else {
                    $user_data->name = $request->name;
                    $user_data->user_phone = $request->user_phone;
                    $user_data->device_token = $request->device_token;
                }

                $conds['phone_id'] = $request->phone_id;
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {
                    return ['error' => __('core__api_banned_user', [], $request->language_symbol)];
                    // return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return ['error' => __('core__api_unpublished_user', [], $request->language_symbol)];
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));

                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {
                    return ['error' => __('core__api_banned_user', [], $request->language_symbol)];

                    // return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return ['error' => __('core__api_unpublished_user', [], $request->language_symbol)];
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $userAccessApiToken = (new UserAccessApiTokenService);
                    $currencyService = (new ItemCurrencyService);
                    $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));


                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        if ($user) {

            $header_token   = $request->headerToken;
            $deviceId = $request->deviceToken;

            $userAccessApiToken = UserAccessApiToken::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->where('device_token', $header_token)
                ->first();

            if (!empty($userAccessApiToken)) {
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->update();
            } else {
                // save in user_access_api_tokens table
                $userAccessApiToken = new UserAccessApiToken();
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->save();
            }

            return $user;
        }
    }

    function appleRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'apple_id' => 'required',
        ]);

        $colorService = (new ColorService);
        $imageService = (new ImageService($colorService));

        $userAccessApiToken = (new UserAccessApiTokenService);
        $currencyService = (new ItemCurrencyService);
        $pushNotificationTokenService = (new PushNotificationTokenService($currencyService, $userAccessApiToken));



        /// validation end

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = $request->email;
        $user_data->apple_id = $request->apple_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->appleVerify;
        $user_data->code = '';
        $added_date = Carbon::now();
        if ($request->password && $request->registerForm == 1) {
            $user_data->password = $request->password;
            $user_data->username = $request->username;
        }

        //Need to check apple_id is aleady exist or not?
        $conds['apple_id'] = $request->apple_id;
        if (!$this->exists($conds)) {
            //User not yet exist

            //prepare validation
            // $validator = Validator::make($request->all(), [
            //     'name' => 'required',
            // ]);

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $apple_id = $request->apple_id;
            $url = $request->profile_photo_url;

            if ($url != "") {
                $img = md5(time()) . '.jpg';
                $imageService->saveImageFromUrl($img, $url);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $request->name;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);

            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->getUser(null, $cond_user_existed);
                $id = empty($user_infos) ? '' : $user_infos->id;
            }

            if ($id != "") {
                //user email alerady exist
                $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $cond_user_existed);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $user_infos->email;
                } else if ($name == "") {
                    $user_data->name = $user_infos->name;
                    $user_data->email = $email;
                } else if ($email == "") {
                    $user_data->name = $name;
                    $user_data->email = $user_infos->email;
                } else {
                    $user_data->name = $name;
                    $user_data->email = $email;
                }

                if ($id != '') {
                    $user_data->id = $id;
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->updateFromApi($user_data, $id);
                } else {
                    if ($request->password && $request->registerForm == 1) {
                        $user_data->password = $request->password;
                        $user_data->username = $request->username;
                    }
                    $user = $this->storeFromApi($user_data);
                }

                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                if ($request->password && $request->registerForm == 1) {
                    $user_data->password = $request->password;
                    $user_data->username = $request->username;
                }
                $user = $this->storeFromApi($user_data);
                $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['apple_id'] = $request->apple_id;
            $backendSetting = $this->backendSettingService->get();
            $user_social_info_override = $backendSetting->user_social_info_override;


            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $imageService->deleteImage($user_cover_photo);

                //Download again
                $apple_id = $request->apple_id;
                $url = $request->profile_photo_url;

                if ($url != "") {

                    $img = md5(time()) . '.jpg';
                    $imageService->saveImageFromUrl($img, $url);
                    $user_data->user_cover_photo = $img;
                }

                $user_data->name = $request->name;
                $user_data->added_date = $added_date;
                $user_data->added_date_timestamp = strtotime($added_date);

                //for user name and user email
                $name = $request->name;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($name == "") {
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($email == "") {
                    $user_data->name = $request->name;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else {
                    $user_data->name = $request->name;
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                }

                $conds['apple_id'] = $request->apple_id;
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->updateFromApi($user_data, $id);
                    } else {
                        if ($request->password && $request->registerForm == 1) {
                            $user_data->password = $request->password;
                            $user_data->username = $request->username;
                        }
                        $user = $this->storeFromApi($user_data);
                    }

                    $pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        if ($user) {

            $header_token   = $request->headerToken;
            $deviceId = $request->deviceToken;

            $userAccessApiToken = UserAccessApiToken::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->where('device_token', $header_token)
                ->first();

            if (!empty($userAccessApiToken)) {
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->update();
            } else {
                // save in user_access_api_tokens table
                $userAccessApiToken = new UserAccessApiToken();
                $userAccessApiToken->device_info = 'Browser';
                $userAccessApiToken->device_id = $deviceId;
                $userAccessApiToken->device_token = $header_token;
                $userAccessApiToken->user_id = $user->id;
                $userAccessApiToken->save();
            }

            return $user;
        }
    }


    public function CreateLogin(Request $request)
    {
        // dd($request->all());

        $dataArr['userLoginData'] = $request->all();

        return renderView(self::indexPath, $dataArr);
    }

    public function createUser(Request $request)
    {


        // dd($request->all());

        if ($request->loginMethod == 'checkFromDashboard') {
            Validator::extend('username_rule', function ($attr, $value) {
                return preg_match('/^[a-zA-Z0-9]+$/', $value);
            });

            $validator = Validator::make($request->all(), [
                'username' => 'username_rule|unique:users',
                // 'password' => 'required|string',
            ]);
        } else {
            Validator::extend('username_rule', function ($attr, $value) {
                return preg_match('/^[a-zA-Z0-9]+$/', $value);
            });

            $validator = Validator::make($request->all(), [
                'username' => 'required|username_rule|string|unique:users',
                'password' => 'required|string',

            ]);
        }





        if ($validator->fails()) {
            // dd($validator->getMessageBag()->getMessages());
            $errors = $validator->getMessageBag()->getMessages();
            // return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
            // return responseMsgApi($errors, $this->badRequestStatusCode);

            return responseMsgApi($errors, $this->okStatusCode, $this->errorStatus);
        }

        if ($request->loginMethod == 'google') {
            $user = $this->googleLoginService($request);
        }
        if ($request->loginMethod == 'phone') {
            $user = $this->phoneRegister($request);
        }
        if ($request->loginMethod == 'facebook') {
            $user = $this->facebookRegister($request);
        }
        if ($request->loginMethod == 'apple') {
            $user = $this->appleRegister($request);
        }
        if ($request->loginMethod == 'normal' || $request->loginMethod == 'checkFromDashboard') {
            $user_data = new \stdClass;
            $user_data->name = $request->name;
            $user_data->email = $request->email;
            $user_data->password = $request->password;
            $user_data->username = $request->username;
            // $user_data->permissions = $this->normalUserRoleId;
            $user_data->status = $this->publish;
            // $user_data->verify_types = $this->googleVerify;
            $user_data->code = '';
            $added_date = Carbon::now();


            $conds['email'] = $request->email;
            $user_infos = $this->getUser(null, $conds);
            // $id = empty($user_infos) ? '' : $user_infos->id;
            // dd($user_infos);
            if ($request->loginMethod == 'checkFromDashboard') {
                // dd($request->login_user_id);

                $user_data->id = $request->login_user_id;
                $role = User::where('id', $user_data->id)->first()->role_id;
                // dd($role);
                $user_data->role_id = $role;
                $user = $this->updateFromApi($user_data, $request->login_user_id);
            } else {
                if ($user_infos) {
                    if ($request->loginMethod == 'normal') {
                        $user_data->id = $user_infos->id;
                        $user = $this->updateFromApi($user_data, $user_data->id);
                    }
                } else {
                    return responseMsgApi('no user', $this->okStatusCode, $this->errorStatus);
                }
            }
        }

        $this->userService->addFreeAdPostCount($user->id);

        return responseMsgApi($user, $this->okStatusCode, $this->successStatus);
    }




    protected function configurePermissions()
    {


        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            "adminMobileToken",
            "userMobileToken",
            "userWebsiteToken",
            "deliboyMobileToken",
        ]);
    }

    public function storeFromApi($request)
    {

        $user = $this->storeCoreFieldValues($request);

        // if(isset($request->user_relation) && !empty($request->user_relation)){
        //     // same from be
        //     $customizeHeaders = $this->getCustomizeFields($this->code,null,null,0);
        //     $customizeHeaderIds = $this->getValueByPluck($customizeHeaders, $this->customUiCoreKeysIdCol);
        //     $this->storeCustomFieldValues($request, $user, $customizeHeaderIds);
        //     // same from be
        // }

        // echo json_encode($request->all());exit;

        // $user = new UserApiResource($this->getUser($user->id, null, $this->userApiRelations));
        return $user;
    }

    public function storeCoreFieldValues($request)
    {
        $user = new User();

        if (isset($request->name) && !empty($request->name))
            $user->name = $request->name;

        if (isset($request->email) && !empty($request->email))
            $user->email = $request->email;

        if (isset($request->username) && !empty($request->username))
            $user->username = $request->username;

        if (isset($request->user_phone) && !empty($request->user_phone))
            $user->user_phone = $request->user_phone;

        if (isset($request->verify_types) && !empty($request->verify_types))
            $user->verify_types = $request->verify_types;

        if (isset($request->google_id) && !empty($request->google_id))
            $user->google_id = $request->google_id;

        if (isset($request->phone_id) && !empty($request->phone_id))
            $user->phone_id = $request->phone_id;

        if (isset($request->facebook_id) && !empty($request->facebook_id))
            $user->facebook_id = $request->facebook_id;

        if (isset($request->password) && !empty($request->password))
            $user->password = Hash::make($request->password);

        if (isset($request->is_show_email))
            $user->is_show_email = $request->is_show_email;

        if (isset($request->is_show_phone))
            $user->is_show_phone = $request->is_show_phone;

        if (isset($request->role_id) && !empty($request->role_id))
            $user->role_id = $request->role_id;
        else
            $user->role_id = $this->normalUserRoleId;

        if (isset($request->user_about_me) && !empty($request->user_about_me))
            $user->user_about_me = $request->user_about_me;

        if (isset($request->code) && !empty($request->code))
            $user->code = $request->code;

        if (isset($request->user_cover_photo) && !empty($request->user_cover_photo)) {
            if (is_string($request->user_cover_photo)) {
                $user->user_cover_photo = $request->user_cover_photo;
            } else {
                $fileName = $this->imageService->insertImageToStorage($request->user_cover_photo);
                if (isset($fileName['error'])) {
                    return 'error';
                }
                $user->user_cover_photo = $fileName;
            }
        }

        if (isset($request->status) && !empty($request->status))
            $user->status = $request->status;

        if (isset($request->overall_rating) && !empty($request->overall_rating))
            $user->overall_rating = $request->overall_rating;

        if (isset($request->added_date) && !empty($request->added_date)) {
            $user->added_date = $request->added_date;
            $user->added_date_timestamp = strtotime($request->added_date);
        } else {
            $user->added_date_timestamp = strtotime(Carbon::now());
        }

        if (isset($request->added_user_id) && !empty($request->added_user_id)) {
            $user->added_user_id = $request->added_user_id;
        } else {
            if (Auth::check()) {
                $user->added_user_id = Auth::user()->id;
            } else {
                $user->added_user_id = 0;
            }
        }

        $user->save();

        $user_permission = new UserPermission();
        if (isset($request->role_id) && !empty($request->role_id)) {
            $user_permission->user_id = $user->id;
            $user_permission->role_id = $user->role_id;
        } else {
            $user_permission->user_id = $user->id;
            $user_permission->role_id = $this->normalUserRoleId;
        }
        $user_permission->save();

        return $user;
    }


    public function updateFromApi($request, $id)
    {

        // $deviceToken = $request->header($this->deviceTokenParaApi);
        // $userId = $request->login_user_id;

        // check permission start
        $user = $this->getUser($id);
        // dd($user);
        // $checkPermission = checkPermissionApi($this->editAbility, $user);
        // $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($id, $deviceToken);
        // $checkOwnerShip = checkOwnerShip($user, $userId);

        // if (empty($userAccessApiToken) || !$checkOwnerShip){
        //     $msg = __('core__api_update_no_permission');
        //     return responseMsgApi($msg, $this->forbiddenStatusCode);
        // }

        // $user = $this->getUser($id);
        // same from be
        $user = $this->updateCoreFieldValues($request);

        // if(isset($request->user_relation) && !empty($request->user_relation)){
        //     $data = $this->updateCustomFieldOldValues($request, $user);
        //     $newDataIndexs = array_diff(array_unique($data['allDataIndex']), array_unique($data['oldDataIndex']));
        //     $this->storeCustomFieldValues($request, $user, $newDataIndexs);
        // }
        // same from be

        // $user = new UserApiResource($this->getUser($user->id, null, $this->userApiRelations));
        return $user;
    }
    public function updateCoreFieldValues($request)
    {
        // dd($request);
        if (isset($request->user_id) && !empty($request->user_id))
            $user = $this->getUser($request->user_id);
        else
            $user = $this->getUser($request->id);

        if (isset($request->name) && !empty($request->name))
            $user->name = $request->name;

        if (isset($request->username) && !empty($request->username))
            $user->username = $request->username;

        if (isset($request->email) && !empty($request->email))
            $user->email = $request->email;

        if (isset($request->google_id) && !empty($request->google_id))
            $user->google_id = $request->google_id;

        if (isset($request->phone_id) && !empty($request->phone_id))
            $user->phone_id = $request->phone_id;

        if (isset($request->facebook_id) && !empty($request->facebook_id))
            $user->facebook_id = $request->facebook_id;

        if (isset($request->password) && !empty($request->password))
            $user->password = Hash::make($request->password);

        if (isset($request->code))
            $user->code = $request->code;

        if (isset($request->status) && !empty($request->status))
            $user->status = $request->status;

        if (isset($request->is_show_email))
            $user->is_show_email = $request->is_show_email;

        if (isset($request->is_show_phone))
            $user->is_show_phone = $request->is_show_phone;

        if (isset($request->role_id) && !empty($request->role_id)) {
            $user->role_id = $request->role_id;
            $user_permission = UserPermission::where('user_id', $user->id)->first();
            // dd($request->role_id);
            $user_permission->role_id = $request->role_id;
            $user_permission->save();
        } else {
            $user->role_id = $this->normalUserRoleId;
        }

        if (isset($request->user_cover_photo) && !empty($request->user_cover_photo)) {
            if (is_string($request->user_cover_photo)) {
                $user->user_cover_photo = $request->user_cover_photo;
            } else {
                $colorService = (new ColorService);
                $imageService = (new ImageService($colorService));
                $fileName = $imageService->insertImageToStorage($request->user_cover_photo);
                if (isset($fileName['error'])) {
                    return 'error';
                }
                $user->user_cover_photo = $fileName;
            }
        }

        if (isset($request->user_about_me) && !empty($request->user_about_me))
            $user->user_about_me = $request->user_about_me;

        if (isset($request->user_phone) && !empty($request->user_phone))
            $user->user_phone = $request->user_phone;

        if (isset($request->overall_rating) && !empty($request->overall_rating))
            $user->overall_rating = $request->overall_rating;

        if (isset($request->verify_types) && !empty($request->verify_types))
            $user->verify_types = $request->verify_types;

        if (isset($request->added_date) && !empty($request->added_date))
            $user->added_date = $request->added_date;

        if (isset($request->added_date_timestamp) && !empty($request->added_date_timestamp))
            $user->added_date_timestamp = $request->added_date_timestamp;

        if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
            $user->updated_user_id = $request->updated_user_id;
        } else {
            if (Auth::check()) {
                $user->updated_user_id = Auth::user()->id;
            } else {
                $user->updated_user_id = $user->added_user_id;;
            }
        }

        $user->update();

        return $user;
    }


    protected function getUser($id = null, $conds = null, $relation = null)
    {

        $user = User::when($id, function ($q, $id) {
            $q->where('id', $id);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();
        return $user;
    }

    // public function getToken(){
    //     $authHandler = new AuthHandler;
    //     $token = $authHandler->GenerateToken();
    //     return $token;
    //     dd($token);
    // }

    protected function updateVerifyType($verify_type, $conds)
    {

        $existingUser = $this->getUser(null, $conds);

        if ($existingUser && $existingUser->is_banned != 1) {
            $verifyTypes = explode(',', $existingUser->verify_types);
            if (!in_array($verify_type, $verifyTypes)) {
                array_push($verifyTypes, $verify_type);
            }

            $verifyTypesString = implode(",", $verifyTypes);

            $existingUser->verify_types = $verifyTypesString;

            $existingUser->update();

            return $existingUser->verify_types;
        }
        return $verify_type;
    }


    protected function exists($conds)
    {
        // dd($conds);
        $password = '';
        if (array_key_exists('password', $conds)) {
            $password = $conds['password'];
            unset($conds['password']);
        }

        $user = User::where($conds)->first();
        if ($user) {
            if ($password != '') {
                if (Hash::check($password, $user['password'])) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }
}
