<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\User;

use App\Config\ps_constant;
use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Constants\Constants;
use Modules\Core\Emails\SendEmailRegistedUserWithVerification;
use Modules\Core\Emails\SendEmailResetPasswordLink;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Http\Services\User\FollowUserService;
use Modules\Core\Http\Services\ResetCodeService;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Http\Services\FavouriteService;
use Modules\Core\Http\Services\Item\ComplaintItemService;
use Modules\Core\Http\Services\TouchService;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Item\PaidItemService;
use Modules\Core\Http\Services\User\BlockUserService;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CoreFieldApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CustomFieldApiResource;

class UserApiController extends Controller
{
    protected $forbiddenStatusCode, $deviceTokenParaApi, $coreFieldFilterSettingService, $translator, $touchService, $userAccessApiTokenService, $userService, $complaintItemService, $favouriteService, $paidItemService, $itemService, $followUserService, $successStatus, $okStatusCode, $internalServerErrorStatusCode, $badRequestStatusCode, $notFoundStatusCode, $resetCodeService, $imageService, $emailVerify, $googleVerify, $facebookVerify, $phoneVerify, $appleVerify, $publish, $normalUserRoleId, $blockUserService;

    public function __construct(protected BackendSettingServiceInterface $backendSettingService, CoreFieldFilterSettingService $coreFieldFilterSettingService, Translator $translator, TouchService $touchService, UserService $userService, FavouriteService $favouriteService, PaidItemService $paidItemService, ComplaintItemService $complaintItemService, ItemService $itemService, FollowUserService $followUserService, BlockUserService $blockUserService, ResetCodeService $resetCodeService, protected PushNotificationTokenServiceInterface $pushNotificationTokenService, ImageService $imageService, UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->translator = $translator;
        $this->touchService = $touchService;
        $this->userService = $userService;
        $this->complaintItemService = $complaintItemService;
        $this->favouriteService = $favouriteService;
        $this->paidItemService = $paidItemService;
        $this->followUserService = $followUserService;
        $this->blockUserService = $blockUserService;
        $this->itemService = $itemService;
        $this->resetCodeService = $resetCodeService;
        $this->imageService = $imageService;
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;

        $this->successStatus = Constants::successStatus;
        $this->okStatusCode = Constants::okStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;

        $this->uploadPathForDel = Constants::uploadPathForDel;
        $this->thumb1xPathForDel = Constants::thumb1xPathForDel;
        $this->thumb2xPathForDel = Constants::thumb2xPathForDel;
        $this->thumb3xPathForDel = Constants::thumb3xPathForDel;

        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;

        $this->emailVerify = Constants::emailVerify;
        $this->googleVerify = Constants::googleVerify;
        $this->facebookVerify = Constants::facebookVerify;
        $this->phoneVerify = Constants::phoneVerify;
        $this->appleVerify = Constants::appleVerify;

        $this->publish = Constants::publishUser;

        $this->normalUserRoleId = Constants::normalUserRoleId;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->code = Constants::user;
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_symbol' => 'required|exists:psx_languages,symbol',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        $msg = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));

        if ($validator->fails()) {
            return responseMsgApi($msg, $this->badRequestStatusCode);
        }

        $custom = [];
        $core = [];
        $vendor = [];

        $entryForCoreAndCustom = $this->userService->createFromApi($request);

        if (isset($entryForCoreAndCustom['custom'])) {

            $custom = CustomFieldApiResource::collection($entryForCoreAndCustom['custom']);
        }
        if (isset($entryForCoreAndCustom['core'])) {

            $core = CoreFieldApiResource::collection($entryForCoreAndCustom['core']);
        }

        return response()->json([
            "custom" => $custom,
            "core" => $core,
            "vendor_list" => $vendor
        ], 200);
    }

    public function login(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user = $this->userService->loginFromApi($request);
        return $user;
    }

    public function register(Request $request)
    {
        Validator::extend('username_rule', function ($attr, $value) {
            return preg_match('/^[a-zA-Z0-9]+$/', $value);
        });

        //prepare validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|username_rule|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user = $this->userService->registerFromApi($request);

        return $user;
    }

    /**
     * User Verified Code
     */
    function verifyCode(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'code' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user_verify_data = array(
            "code"     => $request->code,
            "id"  => $request->user_id,
            // "status"   => 2
        );

        $user = $this->userService->getUser(null, $user_verify_data);

        if (!empty($user)) {
            if ($user->id  == $request->user_id) {
                $user_data = new \stdClass;
                $user_data->code = '';
                $user_data->status = 1;
                $user_data->id = $request->user_id;

                // $user_data['id'] = $user->id;
                $user = $this->userService->updateFromApi($user_data, $user->id);
                return $user;
            }
        }
        return responseMsgApi(__('core__api_invalid', [], $request->language_symbol), $this->badRequestStatusCode);
    }

    /**
     * Users Request Code
     */
    function requestCode(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_email' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user = $this->userService->checkUsernameEmailPhone($request->user_email);
        // $conds['status'] = '1';

        // dd($request->user_email);
        if (!empty($user)) {
            if (empty($user->code)) {
                $code = generate_random_string(5);
                $user_data_code = new \stdClass;
                $user_data_code->id = $user->id;
                $user_data_code->code = $code;

                $user = $this->userService->storeOrUpdateFromApi($user_data_code);
            }
        } else {
            return responseMsgApi(__('core__api_invalid', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
        }

        // send mail
        $to = $user->email;
        $subject = __('core__api_verify_code_sent', [], $request->language_symbol);
        $to_name = $user->name;
        $title = __('core__api_verify_code_sent_title', [], $request->language_symbol);
        $body = __('core__api_need_to_verify', [], $request->language_symbol);
        $subbody = __('core__api_verify_code', [], $request->language_symbol) . ': ' . $user->code;
        if (!sendMail($to, $to_name, $title, $subject, $body, $subbody)) {
            return responseMsgApi(__('core__api_email_not_sent', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        return responseMsgApi(__('core__api_verify_code_sent_to_mail', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
    }

    /**
     * Users Logout
     */
    function logout(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        // delete token from user_access_api_tokens table
        $user = $this->userService->logout($request);
        if ($user) {
            return responseMsgApi(__('core__api_logout_success', [], $request->language_symbol), $this->okStatusCode);
        }
    }

    function updateVerifyType($verify_type, $conds)
    {

        $existingUser = $this->userService->getUser(null, $conds);

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

    /**
     * Users Registration with Phone
     */
    function phoneRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'phone_id' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

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
        if (!$this->userService->exists($conds)) {
            //User not yet exist

            //prepare validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($request->language_symbol) {
                $this->translator->setLocale($request->language_symbol);
                $validator->setTranslator($this->translator);
            }

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $phone_id = $request->phone_id;

            if ($user_data->user_phone != "") {
                $cond_user_existed['user_phone'] = $user_data->user_phone;
                $user_infos = $this->userService->getUser(null, $cond_user_existed);
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
                    $user = $this->userService->updateFromApi($user_data, $id);
                } else {
                    $user = $this->userService->storeFromApi($user_data);
                }

                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user phone not exist
                $user = $this->userService->storeFromApi($user_data);
                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['phone_id'] = $request->phone_id;
            $user_social_info_override = $this->backendSettingService->get()->user_social_info_override;

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
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi(__('core__api_banned_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        $userId = $user->id;
        $deviceId = $request->device_id;
        $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
        if (!$userAccessApiTokenStoreOrUpdate) {
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        // save or update in user_access_api_tokens table end

        $user = new UserApiResource($user);
        return $user;
    }

    /**
     * Users Registration with Apple
     */
    function appleRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'apple_id' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $userData = User::where("email", $request->email)->orWhere("apple_id", $request->apple_id)->first();
        if (!empty($request->name)) {
            $userOfName = $request->name;
        } else {
            if (!empty($userData)) {
                if (!empty($userData->apple_id)) {
                    $userOfName = $userData->apple_id;
                } elseif (!empty($userData->email)) {
                    $userOfName = $userData->email;
                } elseif (!empty($userData->user_phone)) {
                    $userOfName = $userData->user_phone;
                } else {
                    $userOfName = "userName";
                }
            } else {
                if (!empty($request->apple_id)) {
                    $userOfName = $request->apple_id;
                } elseif (!empty($request->email)) {
                    $userOfName = $request->email;
                } elseif (!empty($request->user_phone)) {
                    $userOfName = $request->user_phone;
                } else {
                    $userOfName = "userName";
                }
            }
        }

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = $request->email;
        $user_data->apple_id = $request->apple_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->appleVerify;
        $user_data->code = '';
        $added_date = Carbon::now();

        //Need to check apple_id is aleady exist or not?
        $conds['apple_id'] = $request->apple_id;
        if (!$this->userService->exists($conds)) {
            //User not yet exist

            //prepare validation
            // $validator = Validator::make($request->all(), [
            //     'name' => 'required',
            // ]);

            if ($request->language_symbol) {
                $this->translator->setLocale($request->language_symbol);
                $validator->setTranslator($this->translator);
            }

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $apple_id = $request->apple_id;
            $url = $request->profile_photo_url;

            if ($url != "") {
                $img = md5(time()) . '.jpg';
                $this->imageService->saveImageFromUrl($img, $url);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $userOfName;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);

            $id = "";

            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->userService->getUser(null, $cond_user_existed);
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
                    $user = $this->userService->updateFromApi($user_data, $id);
                } else {
                    $user = $this->userService->storeFromApi($user_data);
                }

                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                $user = $this->userService->storeFromApi($user_data);
                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['apple_id'] = $request->apple_id;
            $user_social_info_override = $this->backendSettingService->get()->user_social_info_override;

            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->userService->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $this->imageService->deleteImage($user_cover_photo);

                //Download again
                $apple_id = $request->apple_id;
                $url = $request->profile_photo_url;

                if ($url != "") {

                    $img = md5(time()) . '.jpg';
                    $this->imageService->saveImageFromUrl($img, $url);
                    $user_data->user_cover_photo = $img;
                }

                $user_data->name = $userOfName;
                $user_data->added_date = $added_date;
                $user_data->added_date_timestamp = strtotime($added_date);

                //for user name and user email
                $name = $userOfName;
                $email = $request->email;

                if ($name == "" && $email == "") {
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($name == "") {
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else if ($email == "") {
                    $user_data->name = $userOfName;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                } else {
                    $user_data->name = $userOfName;
                    $user_data->email = $request->email;
                    $user_data->device_token = $request->device_token;
                    $user_data->user_cover_photo = $request->user_cover_photo;
                }

                $conds['apple_id'] = $request->apple_id;
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        $userId = $user->id;
        $deviceId = $request->device_id;
        $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
        if (!$userAccessApiTokenStoreOrUpdate) {
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        // save or update in user_access_api_tokens table end

        $user = new UserApiResource($user);
        return $user;
    }

    /**
     * Users Registration with Facebook
     */
    function facebookRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'facebook_id' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $app_token = $this->backendSettingService->get()->app_token;

        $user_data = new \stdClass;
        $user_data->name = $request->name;
        $user_data->email = $request->email;
        $user_data->facebook_id = $request->facebook_id;
        $user_data->permissions = $this->normalUserRoleId;
        $user_data->status = $this->publish;
        $user_data->verify_types = $this->facebookVerify;
        $user_data->code = '';
        $added_date = Carbon::now();

        //Need to check facebook_id is aleady exist or not?
        $conds['facebook_id'] = $request->facebook_id;
        if (!$this->userService->exists($conds)) {
            //User not yet exist

            //prepare validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($request->language_symbol) {
                $this->translator->setLocale($request->language_symbol);
                $validator->setTranslator($this->translator);
            }

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $facebook_id = $request->facebook_id;

            if ($request->profile_img_id != "") {
                $url = "https://graph.facebook.com/$request->profile_img_id/picture?width=350&height=500&access_token=" . $app_token;

                $img = md5(time()) . '.jpg';
                $this->imageService->saveImageFromUrl($img, $url);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $request->name;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);

            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->userService->getUser(null, $cond_user_existed);
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
                    $user = $this->userService->updateFromApi($user_data, $id);
                } else {
                    $user = $this->userService->storeFromApi($user_data);
                }

                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                $user = $this->userService->storeFromApi($user_data);
                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['facebook_id'] = $request->facebook_id;
            $user_social_info_override = $this->backendSettingService->get()->user_social_info_override;

            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->userService->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $this->imageService->deleteImage($user_cover_photo);

                //Download again
                $facebook_id = $request->facebook_id;
                if ($request->profile_img_id != "") {
                    $url = "https://graph.facebook.com/$request->profile_img_id/picture?width=350&height=500&access_token=" . $app_token;

                    $img = md5(time()) . '.jpg';
                    $this->imageService->saveImageFromUrl($img, $url);
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

                $conds['facebook_id'] = $request->facebook_id;
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        $userId = $user->id;
        $deviceId = $request->device_id;
        $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
        if (!$userAccessApiTokenStoreOrUpdate) {
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        // save or update in user_access_api_tokens table end

        $user = new UserApiResource($user);
        return $user;
    }

    /**
     * Users Registration with Google
     */
    function googleRegister(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
            // 'device_token' => 'required',
            'platform_name' => 'required',
            'device_info' => 'required',
            'device_id' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
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

        //Need to check google_id is aleady exist or not?
        $conds['google_id'] = $request->google_id;
        if (!$this->userService->exists($conds)) {
            //User not yet exist

            //prepare validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($request->language_symbol) {
                $this->translator->setLocale($request->language_symbol);
                $validator->setTranslator($this->translator);
            }

            if ($validator->fails()) {
                return responseMsgApi($validator->errors(), $this->badRequestStatusCode);
            }
            /// validation end
            $google_id = $request->google_id;
            $url = $request->profile_photo_url;

            if ($url != "") {

                $img = md5(time()) . '.jpg';
                $this->imageService->saveImageFromUrl($img, $url);
                $user_data->user_cover_photo = $img;
            }

            $user_data->name = $request->name;
            $user_data->added_date = $added_date;
            $user_data->added_date_timestamp = strtotime($added_date);

            if ($user_data->email != "") {
                $cond_user_existed['email'] = $user_data->email;
                $user_infos = $this->userService->getUser(null, $cond_user_existed);
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
                    $user = $this->userService->updateFromApi($user_data, $id);
                } else {
                    $user = $this->userService->storeFromApi($user_data);
                }

                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            } else {
                //user email not exist
                $user = $this->userService->storeFromApi($user_data);
                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            }
        } else {
            //User already exist in DB
            $user_data->verify_types = $this->updateVerifyType($user_data->verify_types, $conds);

            $conds['google_id'] = $request->google_id;
            $user_social_info_override = $this->backendSettingService->get()->user_social_info_override;

            if ($user_social_info_override == '1') {
                $user_cover_photo = $this->userService->getUser(null, $conds)->user_cover_photo;

                //Delete existing image
                $this->imageService->deleteImage($user_cover_photo);

                //Download again
                $google_id = $request->google_id;
                $url = $request->profile_photo_url;

                if ($url != "") {

                    $img = md5(time()) . '.jpg';
                    $this->imageService->saveImageFromUrl($img, $url);
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

                $conds['google_id'] = $request->google_id;
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi('Your account is unpublished.', $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            } else {
                $user_datas = $this->userService->getUser(null, $conds);
                $id = $user_datas->id;

                if ($user_datas->is_banned == 1) {

                    return responseMsgApi('core__api_banned_user', $this->badRequestStatusCode);
                } else if ($user_datas->status == 0) {
                    return responseMsgApi(__('core__api_unpublished_user', [], $request->language_symbol), $this->badRequestStatusCode);
                } else {
                    if ($id != '') {
                        $user_data->id = $id;
                        $user = $this->userService->updateFromApi($user_data, $id);
                    } else {
                        $user = $this->userService->storeFromApi($user_data);
                    }

                    $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                }
            }
        }

        // save or update in user_access_api_tokens table start
        $userId = $user->id;
        $deviceId = $request->device_id;
        $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
        if (!$userAccessApiTokenStoreOrUpdate) {
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        // save or update in user_access_api_tokens table end

        $user = new UserApiResource($user);
        return $user;
    }

    public function search(Request $request)
    {
        $users = $this->userService->searchFromApi($request);
        return $users;
    }

    public function deleteUser(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);



        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        $id = $request->user_id;

        $conds['id'] = $id;
        $user = $this->userService->getUser($conds);

        $checkUserByLoginUser = checkUserByLoginUser($id, $request->login_user_id);
        $dir = config("app.dir");

        if (!$checkUserByLoginUser) {
            return responseMsgApi(__('core__api_no_permission', [], $request->language_symbol), $this->forbiddenStatusCode);
        }

        if ($user->user_is_sys_admin == '1') {
            // if user is system admin
            return responseMsgApi(__('core__api_cannot_delete', [], $request->language_symbol), $this->badRequestStatusCode);
        } else if ($dir == ps_constant::searchSubFolder) {
            // if psx-mpc-demo
            $msg = __('core__api_user_delete_success', [], $request->language_symbol);
            return responseMsgApi($msg, $this->okStatusCode, $this->successStatus);
        } else {

            deleteUserRelatedData($id);

            //delete user
            $response = $this->userService->destroyFromApi($request);
            return $response;

            // header token
            $headerToken = ps_constant::deviceTokenKeyFromApi;
            $deviceToken = $request->header($headerToken);
            $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken(null, $deviceToken, null);
            if (!empty($userAccessApiToken)) {
                $userAccessApiToken->delete();
            }
        }
    }

    // user detail api
    public function userDetail(Request $request)
    {
        $user = $this->userService->getFromApi($request);
        return $user;
    }



    /**
     * Users upload Image
     */
    public function userImageUpload(Request $request)
    {

        // return $_FILES;
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'file' => 'required',
            'platform_name' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        $response = $this->userService->uploadUserImageFromApi($request);
        return $response;
    }

    /**
     * Users Password Update
     */
    public function userPasswordUpdate(Request $request)
    {

        // return $_FILES;
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_password' => 'required',
            'conf_password' => 'required|same:user_password',
            'old_password' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        $response = $this->userService->updatePasswordFromApi($request);

        return $response;
    }

    public function userForgotPasswordUpdate(Request $request)
    {

        // return $_FILES;
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_password' => 'required',
            'conf_password' => 'required|same:user_password',
            'code' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        $response = $this->userService->updateForgotPasswordFromApi($request);

        return $response;
    }

    /**
     * Users Profile Update
     */
    public function setUsernamePassword(Request $request)
    {

        $deviceToken = $request->header($this->deviceTokenParaApi);

        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($request->user_id, $deviceToken);

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_update_no_permission', [], $request->language_symbol);
            return ['error' =>  $msg, 'status' =>  $this->forbiddenStatusCode];
        }



        Validator::extend('username_rule', function ($attr, $value) {
            return preg_match('/^[a-zA-Z0-9]+$/', $value);
        });

        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'password' => 'required',
            'username' => 'required|username_rule|unique:users,username,' . $request->user_id

        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        // $user = $this->userService->getUser($request->user_id);



        $response = $this->userService->updateFromApi($request, $request->user_id);
        return $response;
    }

    /**
     * Users Profile Update
     */
    public function userProfileUpdate(Request $request)
    {

        //prepare validation for custom filed
        $errors = validateForCustomField($this->code, $request->user_relation);

        $coreFieldsIds = [];
        $errors = [];

        $cond['module_name'] = $this->code;
        $cond['mandatory'] = 1;
        $cond['is_core_field'] = 1;

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);

        // $coreFields = CoreField::where('module_name',)->where('mandatory',1)->where('is_core_field',1)->get();
        foreach ($coreFields as $key => $value) {
            if (str_contains($value->field_name, "@@")) {
                $originFieldName = strstr($value->field_name, "@@", true);
            } else {
                $originFieldName = $value->field_name;
            }
            array_push($coreFieldsIds, $originFieldName);
        }

        $validationArr = [];
        Validator::extend('username_rule', function ($attr, $value) {
            return preg_match('/^[a-zA-Z0-9]+$/', $value);
        });

        $validationArr['user_id'] = 'required|exists:users,id';

        // if(in_array('username',$coreFieldsIds)){
        //     $validationArr['username'] = 'required|username_rule|unique:users,username,'.$request->user_id;
        // }else{
        $validationArr['username'] = 'nullable|sometimes|username_rule|unique:users,username,' . $request->user_id;
        // }

        if (in_array('name', $coreFieldsIds)) {
            $validationArr['name'] = 'required';
        }

        if (in_array('email', $coreFieldsIds)) {
            $validationArr['email'] = 'required|email';
        } else {
            $validationArr['email'] = 'nullable|sometimes|email';
        }

        if (in_array('user_phone', $coreFieldsIds)) {
            $validationArr['user_phone'] = 'required';
        }

        if (in_array('user_about_me', $coreFieldsIds)) {
            $validationArr['user_about_me'] = 'required';
        }

        if (in_array('is_show_email', $coreFieldsIds)) {
            $validationArr['is_show_email'] = 'required';
        }

        if (in_array('is_show_phone', $coreFieldsIds)) {
            $validationArr['is_show_phone'] = 'required';
        }


        //prepare validation for core filed
        $validator = Validator::make(
            $request->all(),
            $validationArr

        );
        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            $validationError = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));
            $errorMsg = implode("\n", $errors);
            if ($errorMsg) {
                $validationError = $validationError . "\n" . $errorMsg;
            }
            return responseMsgApi($validationError, $this->badRequestStatusCode);
        } else {
            if ($errors) {
                $errorMsg = implode("\n", $errors);
                if ($errorMsg) {
                    $validationError = $validationError . "\n" . $errorMsg;
                }
                return responseMsgApi($validationError, $this->badRequestStatusCode);
            }
        }

        /// validation end

        $user = $this->userService->getUser($request->user_id);

        if ($user->email != $request->email) {
            //if email changed, check email
            $checkEmail['email'] = $request->email;
            if ($this->userService->exists($checkEmail)) {
                return responseMsgApi(__('core__api_email_already_existed', [], $request->language_symbol), $this->badRequestStatusCode);
            }
        }
        if ($user->user_phone != '' && $user->user_phone != $request->user_phone) {
            //if email changed, check email
            $checkPhone['user_phone'] = $request->user_phone;
            if ($this->userService->exists($checkPhone)) {
                return responseMsgApi(__('core__api_phone_already_existed', [], $request->language_symbol), $this->badRequestStatusCode);
            }
        }

        $response = $this->userService->updateFromApi($request, $request->user_id);
        return $response;
    }

    /**
     * User Reset Password
     */
    function resetPassword(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $conds['email'] = $request->email;

        // generate code
        $code = md5(time() . 'teamps');

        $user = $this->userService->getUser(null, $conds);

        // insert to reset
        $data = new \stdClass;
        $data->id = $user->id;
        $data->code = $code;

        $this->resetCodeService->create($data);

        // send mail
        $to = $request->email;
        $subject = __('core__api_reset_code', [], $request->language_symbol);
        $to_name = $user->name;
        $title = __('core__api_reset_code_title', [], $request->language_symbol);
        $flag = "reset";

        if (!sendMail($to, $to_name, $title, $subject, null, null, $flag)) {
            return responseMsgApi(__('core__api_email_not_sent', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }

        return responseMsgApi(__('core__api_success_reset_email_sent', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
    }

    function forgotPassword(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $user = $this->userService->checkUsernameEmailPhone($request->email);

        // generate code
        $codeMd5 = md5(time() . 'teamps');
        $code = substr($codeMd5, 0, 8);

        if ($user && $user->email != null) {
            $user->password_reset_code = Hash::make($code);
            $user->update();
        } else if ($user && $user->email == null) {
            return responseMsgApi(__("core_user__user_not_have_email", [], $request->language_symbol), $this->badRequestStatusCode);
        } else {
            return responseMsgApi(__('core__api_invalid_user', [], $request->language_symbol), $this->badRequestStatusCode);
        }

        // send mail
        $to = $user->email;
        $subject = __('core__api_forgot_password', [], $request->language_symbol);
        $to_name = $user->name;
        $title = __('core__api_forgot_password_title', [], $request->language_symbol);
        $flag = "forgot_password";

        $msg = __('core__your_password_reset_code_is', [], $request->language_symbol) . ' ' . $code;

        if (!sendMail($to, $to_name, $title, $subject, $msg, null, $flag)) {
            return responseMsgApi(__('core__api_email_not_sent', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }

        return responseMsgApi(__('core__api_success_email_sent', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
    }

    function forgotPasswordVerify(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'code' => 'required',
        ]);

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end
        $user = $this->userService->checkUsernameEmailPhone($request->email);
        if ($user && $user->email != null) {
            if (Hash::check($request->code, $user->password_reset_code)) {
                $user = new UserApiResource($user);
                return responseDataApi($user);
            }
        } else if ($user && $user->email == null) {
            return responseMsgApi(__("core_user__user_not_have_email", [], $request->language_symbol), $this->badRequestStatusCode);
        } else {
            return responseMsgApi(__('core__api_invalid_user', [], $request->language_symbol), $this->badRequestStatusCode);
        }

        return responseMsgApi(__('core__api_code_not_same', [], $request->language_symbol), $this->badRequestStatusCode);
    }

    public function getTopRatedSeller(Request $request)
    {
        $users = $this->userService->getTopRatedSellerFromApi($request);
        return $users;
    }
}
