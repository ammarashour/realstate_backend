<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_config;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\Facades\Image;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\User\BlueMarkUser;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Configuration\SystemConfig;
use Modules\Core\Entities\UserAccessApiToken;
use Modules\Core\Entities\UserInfo;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Exports\BuyerReportExport;
use Modules\Core\Exports\DailyActiveUserReportExport;
use Modules\Core\Exports\SellerReportExport;
use Modules\Core\Exports\UserReportExport;
use Modules\Core\Http\Services\Authorization\PushNotificationTokenService;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Transformers\Backend\Model\User\UserWithKeyResource;
use Modules\Core\Http\Services\CoreKeyUserRelationService;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Transformers\Backend\NoModel\BannedUser\BannedUserWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\BuyerReport\BuyerReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\DailyActiveUserReport\DailyActiveUserReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\SellerReport\SellerReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\UserReport\UserReportWithKeyResource;
use Throwable;

/**
 * @deprecated
 */
class UserService extends PsService
{
    protected  $customUiDetailCoreKeysIdCol, $userUserIdCol, $dailyActiveUserReportCsvFileName, $userAddedUserIdCol, $userReportCsvFileName, $imageService, $show, $hide, $enable, $disable, $delete, $unDelete, $uploadPathForDel, $thumb1xPathForDel, $thumb2xPathForDel, $thumb3xPathForDel, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $originPath, $thumbnail1xPath, $thumbnail2xPath, $thumbnail3xPath, $usrTableName, $userNameCol, $userRoleIdCol, $userEmailCol, $userPhoneCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $componentName, $coreFieldFilterModuleNameCol, $coreFieldFilterIdCol, $coreFieldFilterForRelation, $paginationPerPage, $screenDisplayUiKeyCol, $usrInfoTableName, $usrInfoCoreKeysIdCol, $usrInfoUserIdCol, $getImgPath, $parentPath, $customUiMandatoryCol, $customUiIsDelCol, $customUiEnableCol, $customUiCoreKeysIdCol, $customUiIdCol, $code, $corekeyRelationService, $successStatus, $userAddedDateCol, $searchHistoryUserType, $searchHistoryService, $userAccessApiTokenService, $unBan, $ban, $dangerFlag, $userIdCol, $usrIsVerifyBlueMark, $usrBlueMarkNote, $userStatusCol, $userIsBannedCol, $internalServerErrorStatusCode, $noContentStatusCode, $createdStatusCode, $notFoundStatusCode, $okStatusCode, $badRequestStatusCode, $pushNotificationTokenService, $backendSettingService, $dropDownUi, $textUi, $radioUi, $checkBoxUi, $dateTimeUi, $numberUi, $textAreaUi, $multiSelectUi, $imageUi, $timeOnlyUi, $dateOnlyUi, $normalUserRoleId, $superAdminRoleId, $roleService, $customUiModuleName, $userApiRelations, $publish, $pending, $emailVerify,  $deviceTokenParaApi, $loginUserIdParaApi, $forbiddenStatusCode, $buyerReportCsvFileName, $sellerReportCsvFileName;

    public function __construct(SearchHistoryService $searchHistoryService, CoreKeyUserRelationService $corekeyRelationService, PushNotificationTokenService $pushNotificationTokenService, BackendSettingService $backendSettingService, ImageService $imageService, RoleService $roleService, UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->dangerFlag = Constants::danger;
        $this->ban = Constants::Ban;
        $this->unBan = Constants::unBan;
        $this->usrIsVerifyBlueMark = Constants::usrIsVerifyBlueMark;
        $this->usrBlueMarkNote = Constants::usrBlueMarkNote;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;
        $this->noContentStatusCode = Constants::noContentStatusCode;

        $this->pushNotificationTokenService = $pushNotificationTokenService;
        $this->backendSettingService = $backendSettingService;
        $this->corekeyRelationService = $corekeyRelationService;
        $this->searchHistoryService = $searchHistoryService;

        $this->searchHistoryUserType = Constants::searchHistoryUserType;

        $this->code = Constants::user;

        $this->customUiIdCol = CustomField::id;
        $this->customUiCoreKeysIdCol = CustomField::coreKeysId;
        $this->customUiEnableCol = CustomField::enable;
        $this->customUiUiTypeIdCol = CustomField::uiTypeId;
        $this->customUiIsDelCol = CustomField::isDelete;
        $this->customUiMandatoryCol = CustomField::mandatory;
        $this->customUiModuleName = CustomField::moduleName;

        $this->customUiDetailTableName = CustomFieldAttribute::tableName;
        $this->customUiDetailCoreKeysIdCol = CustomFieldAttribute::coreKeysId;
        $this->customUiDetailIdCol = CustomFieldAttribute::id;

        $this->parentPath = Constants::parentPath;
        $this->getImgPath = Constants::imgPath;

        $this->usrInfoTableName = UserInfo::tableName;
        $this->usrInfoUserIdCol = UserInfo::userId;
        $this->usrInfoValueCol = UserInfo::value;
        $this->usrInfoCoreKeysIdCol = UserInfo::coreKeysId;
        $this->usrInfoUserIdCol = UserInfo::userId;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;
        $this->coreFieldFilterIdCol = CoreField::id;
        $this->coreFieldFilterForRelation = ps_config::coreFieldFilterForRelation;
        $this->paginationPerPage = ps_config::pagPerPage;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->componentName = "user";

        $this->usrTableName = User::tableName;
        $this->userNameCol = User::name;
        $this->userStatusCol = User::status;
        $this->userIsBannedCol = User::isBanned;
        $this->userRoleIdCol = User::roleId;
        $this->userIdCol = User::id;
        $this->userAddedDateCol = User::addedDate;
        $this->userEmailCol = User::email;
        $this->userPhoneCol = User::userPhone;
        $this->userAddedUserIdCol = User::addedUser;
        $this->userUserIdCol = User::id;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;

        $this->originPath = Constants::originPath;
        $this->thumbnail1xPath = Constants::thumbnail1xPath;
        $this->thumbnail2xPath = Constants::thumbnail2xPath;
        $this->thumbnail3xPath = Constants::thumbnail3xPath;

        $this->uploadPathForDel = Constants::uploadPathForDel;
        $this->thumb1xPathForDel = Constants::thumb1xPathForDel;
        $this->thumb2xPathForDel = Constants::thumb2xPathForDel;
        $this->thumb3xPathForDel = Constants::thumb3xPathForDel;

        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->enable = Constants::enable;
        $this->disable = Constants::disable;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->superAdminRoleId = Constants::superAdminRoleId;
        $this->normalUserRoleId = Constants::normalUserRoleId;

        $this->dropDownUi = Constants::dropDownUi;
        $this->textUi = Constants::textUi;
        $this->radioUi = Constants::radioUi;
        $this->checkBoxUi = Constants::checkBoxUi;
        $this->dateTimeUi = Constants::dateTimeUi;
        $this->textAreaUi = Constants::textAreaUi;
        $this->numberUi = Constants::numberUi;
        $this->multiSelectUi = Constants::multiSelectUi;
        $this->imageUi = Constants::imageUi;
        $this->timeOnlyUi = Constants::timeOnlyUi;
        $this->dateOnlyUi = Constants::dateOnlyUi;
        $this->imageService = $imageService;
        $this->roleService = $roleService;

        $this->userApiRelations = ['userRelation'];

        $this->publish = Constants::publishUser;
        $this->pending = Constants::pendingUser;
        $this->emailVerify = Constants::emailVerify;

        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->buyerReportCsvFileName = "buyer_report";
        $this->sellerReportCsvFileName = "seller_report";
        $this->userReportCsvFileName = "user_report";
        $this->dailyActiveUserReportCsvFileName = "daily_active_user_report";

        $this->userPermissionUserIdCol = UserPermission::userId;

        $this->roleTableName = Role::tableName;
        $this->roleIdCol = Role::id;
        $this->roleNameCol = Role::name;
    }

    // for Backend
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

        if (isset($request->apple_id) && !empty($request->apple_id))
            $user->apple_id = $request->apple_id;

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

        $this->storeOrUpdateUserPermission($user->id, $user->role_id);

        return $user;
    }

    public function updateCoreFieldValues($request)
    {

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

        if (isset($request->apple_id) && !empty($request->apple_id))
            $user->apple_id = $request->apple_id;

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
            if ($user_permission) {
                $user_permission->role_id = $request->role_id;
                $user_permission->updated_user_id = Auth::user() == null ? '1' : Auth::user()->id;
                $user_permission->update();
            } else {
                $new_user_permission = new UserPermission();
                $new_user_permission->role_id = $request->role_id;
                $new_user_permission->user_id = $user->id;
                $new_user_permission->added_user_id = Auth::user() == null ? '1' : Auth::user()->id;
                $new_user_permission->save();
            }
        } else {
            $user->role_id = $this->normalUserRoleId;
        }

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

        $this->storeOrUpdateUserPermission($user->id, $user->role_id);

        return $user;
    }

    public function updateCoreFieldValuesForRating($request)
    {

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


    public function updateCustomFieldOldValues($request, $user)
    {

        $allDataIndex = [];
        $oldDataIndex = [];
        if (isset($request->user_relation)) {
            foreach ($request->user_relation as $key => $value) {
                if (is_array($value)) {
                    $coreKeysIdFromReq = $value['core_keys_id'];
                    $valueFromReq = $value['value'];

                    array_push($allDataIndex, $value['core_keys_id']);
                } else {
                    $coreKeysIdFromReq = $key;
                    $valueFromReq = $value;

                    array_push($allDataIndex, $key);
                }

                if ($valueFromReq !== null && $valueFromReq !== "undefined") {

                    $userRelations = UserInfo::where($this->usrInfoUserIdCol, $user->id)->get();

                    foreach ($userRelations as $key3 => $userRelation) {
                        array_push($oldDataIndex, $userRelation->core_keys_id);

                        if ($coreKeysIdFromReq == $userRelation->core_keys_id) {

                            if (is_file($valueFromReq)) {
                                // del file from local first
                                delImageFromCustomFieldValue($userRelation, $this->uploadPathForDel, $this->thumb1xPathForDel, $this->thumb2xPathForDel, $this->thumbnail3xPath);

                                // save new file in local again
                                $file = $this->checkFileInCustomFieldValue($valueFromReq);
                            }

                            // update in db start
                            $userRelation->user_id = $user->id;
                            if ($valueFromReq === false) {
                                $userRelation->value = 0;
                            }
                            if (is_file($valueFromReq)) {
                                if (str_contains($valueFromReq->getMimeType(), 'image')) {
                                    $userRelation->value = $file;
                                }
                            }
                            if (!is_file($valueFromReq) && $valueFromReq !== false) {
                                $userRelation->value = $valueFromReq;
                            }

                            $customizeHeaders = CustomField::where($this->customUiModuleName, $this->code)->get();
                            foreach ($customizeHeaders as $key2 => $customizeHeader) {
                                if ($coreKeysIdFromReq === $customizeHeader->core_keys_id) {
                                    $userRelation->ui_type_id = $customizeHeader->ui_type_id;
                                    $userRelation->core_keys_id = $customizeHeader->core_keys_id;
                                }
                            }
                            if (Auth::check()) {
                                $userRelation->updated_user_id = Auth::id();
                            } else {
                                $userRelation->updated_user_id = 0;
                            }
                            $userRelation->update();
                            // update in db end
                        }
                    }
                } else if ($valueFromReq === null) {
                    $userRelations = UserInfo::where($this->usrInfoUserIdCol, $user->id)->get();

                    foreach ($userRelations as $key3 => $userRelation) {
                        array_push($oldDataIndex, $userRelation->core_keys_id);

                        if ($coreKeysIdFromReq == $userRelation->core_keys_id) {

                            // update in db start
                            $userRelation->user_id = $user->id;
                            $userRelation->value = $valueFromReq;

                            $customizeHeaders = CustomField::where($this->customUiModuleName, $this->code)->get();
                            foreach ($customizeHeaders as $key2 => $customizeHeader) {
                                if ($coreKeysIdFromReq === $customizeHeader->core_keys_id) {
                                    $userRelation->ui_type_id = $customizeHeader->ui_type_id;
                                    $userRelation->core_keys_id = $customizeHeader->core_keys_id;
                                }
                            }
                            if (Auth::check()) {
                                $userRelation->updated_user_id = Auth::id();
                            } else {
                                $userRelation->updated_user_id = 0;
                            }
                            $userRelation->update();
                            // update in db end
                        }
                    }
                }
            }
        }

        $dataArr = [
            'allDataIndex' => $allDataIndex,
            'oldDataIndex' => $oldDataIndex
        ];

        return $dataArr;
    }

    public function updateCustomFieldNewValues($request, $user, $newDataIndexs)
    {
        foreach ($newDataIndexs as $key3 => $newDataIndex) {
            foreach ($request->user_relation as $key => $value) {

                if ($key3 == $key) {
                    $userRelation = new UserInfo();

                    $file = $this->checkFileInCustomFieldValue($value);

                    $userRelation->user_id = $user->id;

                    if ($value === false) {
                        $userRelation->value = 0;
                    }
                    if (is_file($value)) {
                        if (str_contains($value->getMimeType(), 'image')) {
                            $userRelation->value = $file;
                        }
                    }
                    if (!is_file($value) && $value !== false) {
                        $userRelation->value = $value;
                    }

                    $customizeHeaders = CustomField::where($this->customUiModuleName, $this->code)->get();
                    foreach ($customizeHeaders as $key2 => $customizeHeader) {
                        if ($key === $key2) {
                            $userRelation->ui_type_id = $customizeHeader->ui_type_id;
                            $userRelation->core_keys_id = $customizeHeader->core_keys_id;
                        }
                    }

                    if (Auth::check()) {
                        $userRelation->added_user_id = Auth::id();
                    } else {
                        $userRelation->added_user_id = 0;
                    }

                    $userRelation->save();
                }
            }
        }
    }

    public function updateUsernamePassword($request) {}

    public function storeOrUpdateUserPermission($user_id, $role_id)
    {
        $userPermissions = UserPermission::where($this->userPermissionUserIdCol, $user_id)->first();
        if (empty($userPermissions)) {
            // if not have old data, will save
            $userPermission = new UserPermission();
            $userPermission->user_id = $user_id;
            $userPermission->role_id = $role_id;
            $userPermission->added_user_id = Auth::user() == null ? '1' : Auth::user()->id;
            $userPermission->save();
        } else {
            // if have old data, will update
            $userPermissions->user_id = $user_id;
            $userPermissions->role_id = $role_id;
            $userPermissions->updated_user_id = Auth::user() == null ? '1' : Auth::user()->id;
            $userPermissions->update();
        }
    }

    public function checkFileInCustomFieldValue($value)
    {

        if (str_contains($value->getMimeType(), 'image')) {
            $img = Image::make($value);

            // change file to new name
            $file = uniqid() . "_user." . $value->getClientOriginalExtension();

            saveImgAsOriginalThumbNail1x2x3x($value, $file, $this->originPath, $this->thumbnail1xPath, $this->thumbnail2xPath, $this->thumbnail3xPath);
        } else if (str_contains($value->getMimeType(), 'video')) {
            return 'This is video';
        } else {
            return 'other';
        }
        return $file;
    }

    public function takingForColumnAndFilterOption($flag = null)
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showUserCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        if (isset($flag) && !empty($flag) && $flag == 'banned_user') {
            $controlFieldObj = $this->takingForColumnProps(__('core__be_view_lable'), "detail", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } else if (isset($flag) && !empty($flag) && $flag == 'user_report') {
            $controlFieldObj = $this->takingForColumnProps(__('core__be_view_lable'), "detail", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } else {
            $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        }

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;
                $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;

                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showUserCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }

                if ($showFields->customizeField->ui_type_id == $this->multiSelectUi) {
                    $type = "MultiSelect";
                } elseif ($showFields->customizeField->ui_type_id == $this->imageUi) {
                    $type = "Image";
                } else {
                    $type = $showFields->customizeField->data_type;
                }

                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showUserCols,
        ];
        return $dataArr;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }

    public function deleteCustomizeFieldData($userRelations)
    {
        foreach ($userRelations as $userRelation) {

            delImageFromCustomFieldValue($userRelation, $this->uploadPathForDel, $this->thumb1xPathForDel, $this->thumb2xPathForDel, $this->thumbnail3xPath);
            $userRelation->delete();
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            // save in user table
            $user = $this->storeCoreFieldValues($request);

            // save or update user_permissions
            if (isset($request->role_id) && !empty($request->role_id)) {
                $this->storeOrUpdateUserPermission($user->id, $request->role_id);
            }

            // save in user_infos table
            $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
            $customizeHeaderIds = $this->getValueByPluck($customizeHeaders, $this->customUiCoreKeysIdCol);

            $this->storeCustomFieldValues($request, $user, $customizeHeaderIds);

            DB::commit();
            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($request)
    {
        DB::beginTransaction();

        try {
            // update in user table
            if (!isset($request->role_id) || empty($request->role_id)) {
                $request['role_id'] = $this->getUser($request->id)->role_id;
            }
            // dd($data);

            $user = $this->updateCoreFieldValues($request);

            // save or update user_permissions
            if (isset($request->role_id) && !empty($request->role_id)) {
                $this->storeOrUpdateUserPermission($user->id, $request->role_id);
            }

            // save in core__be_user_info table
            $data = $this->updateCustomFieldOldValues($request, $user);

            $newDataIndexs = array_diff(array_unique($data['allDataIndex']), array_unique($data['oldDataIndex']));

            $this->storeCustomFieldValues($request, $user, $newDataIndexs);

            if ($request->file('user_cover_photo')) {
                $file = $request->file('user_cover_photo');
                $fileName = $this->imageService->insertImageToStorage($file, $user->user_cover_photo);
                $user->user_cover_photo = $fileName;
                $user->update();
            }




            DB::commit();
            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            // echo json_encode($e->getMessage());
            // exit;
            return ['error' => $e->getMessage()];
        }
    }

    public function index($routeName, $request)
    {

        $code = $this->code;
        $customizeUis = CustomField::where($this->customUiModuleName, $code)->where($this->customUiUiTypeIdCol, 'uit00001')->latest()->get();
        $userRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();

        $customizeUisByModule = CustomField::where($this->customUiModuleName, $code)->latest()->get();
        $customizeDetails = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUisByModule->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();

        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, User::class, "admin.index");

        $date_range = null;
        if (!empty($request->date_filter) && $request->date_filter != 'all') {
            $start_date = $request->date_filter[0];
            $end_date = $request->date_filter[1];
            if (empty($end_date)) {
                $end_date = Carbon::now();
            }
            $date_range = [$start_date, $end_date];
        }

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['role_id'] = $request->input('role_filter') == 'all' ? null  : $request->role_filter;
        $conds['date_range'] = $request->input('date_filter') == 'all' ? null  : $date_range;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 0;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation'
        ];


        $users = UserWithKeyResource::collection($this->getUsers($userRelation, null, false, $conds, null, null, null, false, $row));


        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing user arr object with new format
        $changedUserObj = $users;
        $roles = $this->roleService->getRoles();

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,
                'usrIsVerifyBlueMark' => $this->usrIsVerifyBlueMark,
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,
                'usrIsVerifyBlueMark' => $this->usrIsVerifyBlueMark,
                'customizeDetails' => $customizeDetails,
                'customizeHeaders' => $customizeUisByModule
            ];
        }
        return $dataArr;
    }

    public function create($routeName)
    {
        $code = $this->code;

        // check permission
        $checkPermission = $this->checkPermission($this->createAbility, User::class, "admin.index");

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();
        $roles = Role::all();

        $dataArr = [
            'checkPermission' => $checkPermission,
            'roles' => $roles,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];

        return $dataArr;
    }

    public function edit($id, $routeName)
    {
        $dataWithRelation = ["userRelation"];
        $user = $this->getUser($id, null, $dataWithRelation);
        $code = $this->code;

        // check permission start
        $checkPermission = $this->checkPermission($this->editAbility, $user, "admin.index");
        // check permission end

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();
        $roles = Role::all();
        $yourPermissions = UserPermission::where('user_id', $id)->first();

        $conds = [
            'module_name' => Constants::user,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = CoreField::where($conds)->get();

        $validation = [];

        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'user_cover_photo') {
                array_push($validation, 'user_cover_photo');
            }
        }

        $dataArr = [
            'checkPermission' => $checkPermission,
            'user'   => $user,
            'roles'   => $roles,
            'yourPermissions' => $yourPermissions,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'validation' => $validation
        ];

        return $dataArr;
    }

    public function destroy($id, $routeName)
    {
        //delete in users table
        $user = $this->getUser($id);

        // check permission
        $checkPermission = $this->checkPermission($this->deleteAbility, $user, "admin.index");

        deleteUserRelatedData($id);

        $name = $user->name;
        $user->delete();

        //delete in user_relations table
        $userRelations = $this->getValuesForCustomizeField('', $user->id);
        $this->deleteCustomizeFieldData($userRelations);

        $dataArr = [
            "checkPermission" => $checkPermission,
            'msg' => __('core__be_delete_success', ['attribute' => $name]),
            'flag' => $this->dangerFlag,
        ];

        return $dataArr;
    }

    public function deleteImage($request)
    {
        $user = User::where($this->userIdCol, $request->id)->first();

        $user->user_cover_photo = NULL;

        $user->update();

        $this->imageService->deleteImage($request->user_cover_photo);
    }

    public function replaceImage($user, $request)
    {
        $user = User::where($this->userIdCol, $user->id)->first();


        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName = $this->imageService->insertImageToStorage($file, $user->user_cover_photo);
            $user->user_cover_photo = $fileName;
            $user->update();
        }
    }

    public function updateForgotPasswordFromApi($request)
    {

        DB::beginTransaction();
        try {
            $user = $this->getUser($request->user_id);


            $conds['id'] = $request->user_id;

            $user = User::where($conds)->first();
            if ($user) {
                if (Hash::check($request->code, $user->password_reset_code)) {
                    $user->password_reset_code = Null;
                    $user->password = Hash::make($request->user_password);
                } else {
                    return responseMsgApi('invalid code', $this->badRequestStatusCode);
                }
            } else {
                return responseMsgApi('invalid user', $this->badRequestStatusCode);
            }

            $user->update();



            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }

        return responseMsgApi(__('core__api_password_updated', [], $request->language_symbol), $this->createdStatusCode, $this->successStatus);
    }

    public function updatePasswordFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_update_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            DB::beginTransaction();

            try {
                $user = $this->getUser($request->user_id);

                $checkUserByLoginUser = checkUserByLoginUser($request->user_id, $request->login_user_id);

                if (!$checkUserByLoginUser) {
                    return responseMsgApi(__('core__api_no_permission', [], $request->language_symbol), $this->forbiddenStatusCode);
                } else {

                    if (Hash::check($request->old_password, $user->password)) {
                        if ($request->user_password) {
                            $user->password = Hash::make($request->user_password);
                        } else {
                            return responseMsgApi('invalid password', $this->internalServerErrorStatusCode);
                        }
                        $user->update();
                    } else {
                        return responseMsgApi(__('core__api_incorrect_old_password', [], $request->language_symbol), $this->badRequestStatusCode);
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
            }

            return responseMsgApi(__('core__api_password_updated', [], $request->language_symbol), $this->createdStatusCode, $this->successStatus);
        }
    }

    public function uploadUserImageFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            DB::beginTransaction();

            try {
                $user = $this->getUser($request->user_id);

                if ($_FILES) {
                    if (!empty($user->user_cover_photo) && $user->user_cover_photo != '') {
                        $this->imageService->deleteImage($user->user_cover_photo);
                    }
                    $file = file_get_contents($_FILES['file']['tmp_name']);
                    $img = md5(time()) . '.jpg';
                    $this->imageService->saveImage($file, $img);
                    $user->user_cover_photo = $img;
                }
                $user->update();

                DB::commit();
                $user = new UserApiResource($user);
                return $user;
            } catch (\Throwable $e) {
                DB::rollBack();
                return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
            }
        }
    }

    public function getSqlForCustomField()
    {
        $sql = "";
        $customizeUis = CustomField::where($this->customUiModuleName, $this->code)->latest()->get();

        $customizeuideatil_array = [];

        foreach ($customizeUis as $CustomFieldAttribute) {
            if ($CustomFieldAttribute->ui_type_id == $this->dropDownUi || $CustomFieldAttribute->ui_type_id == $this->radioUi || $CustomFieldAttribute->ui_type_id == $this->multiSelectUi) {
                $customizeuideatil_array[$CustomFieldAttribute->core_keys_id . '@@name'] = $CustomFieldAttribute->core_keys_id;
            }
        }

        foreach (array_unique($customizeuideatil_array) as $key => $customizeuideatil) {
            $sql .= "max(case when psx_user_infos.core_keys_id = '$customizeuideatil' then psx_customize_ui_details.name end) as '$key',";
        }
        foreach ($customizeUis as $key => $customizeUi) {
            if ($key + 1 == count($customizeUis)) {
                $sql .= "max(case when psx_user_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_user_infos.value end) as '$customizeUi->core_keys_id'";
            } else {
                $sql .= "max(case when psx_user_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_user_infos.value end) as '$customizeUi->core_keys_id' ,";
            }
        }
        return $sql;
    }

    public function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->usrTableName . '.' . $this->userNameCol, 'like', '%' . $search . '%')
                    ->orWhere($this->usrTableName . '.' . $this->userEmailCol, 'like', '%' . $search . '%')
                    ->orWhere($this->usrTableName . '.' . $this->userPhoneCol, 'like', '%' . $search . '%');
            });
        }

        // keyword
        if (isset($conds['keyword']) && $conds['keyword']) {
            $search = $conds['keyword'];
            $query->where(function ($query) use ($search) {
                $query->where($this->usrTableName . '.' . $this->userNameCol, 'like', '%' . $search . '%');
            });
        }
        //email
        if (isset($conds['email']) && $conds['email']) {
            $email_filter = $conds['email'];
            $query->where($this->usrTableName . '.' . $this->userEmailCol, $email_filter);
        }

        //phone
        if (isset($conds['user_phone']) && $conds['user_phone']) {
            $phone_filter = $conds['user_phone'];
            $query->where($this->usrTableName . '.' . $this->userPhoneCol, $phone_filter);
        }

        if (isset($conds['role_id']) && $conds['role_id']) {
            $role_filter = $conds['role_id'];
            $query->whereHas('role', function ($q) use ($role_filter) {
                $q->where($this->usrTableName . '.' . $this->userRoleIdCol, $role_filter);
            });
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->where($this->usrTableName . '.' . $this->userAddedDateCol, $date_filter);
        }

        if (isset($conds['date_range']) && $conds['date_range']) {
            $date_filter = $conds['date_range'];
            if ($date_filter[1] == '') {
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->usrTableName . '.' . $this->userAddedDateCol, $date_filter);
        }

        if (isset($conds['status'])) {
            $query->where($this->usrTableName . '.' . $this->userStatusCol, $conds['status']);
        }

        if (isset($conds['is_banned'])) {
            $query->where($this->usrTableName . '.' . $this->userIsBannedCol, $conds['is_banned']);
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->usrTableName . '.' . $this->userAddedUserIdCol, $conds['added_user_id']);
        }

        if (isset($conds['user_relation']) && !empty($conds['user_relation'])) {
            $customizeUis = CustomField::where('module_name', $this->code)->latest()->get();
            foreach ($conds['user_relation'] as $key => $value) {

                if (!empty($value['value'])) {
                    foreach ($customizeUis as $CustomFieldAttribute) {
                        if ($value['core_keys_id'] == $CustomFieldAttribute->core_keys_id) {
                            if ($CustomFieldAttribute->ui_type_id == $this->dropDownUi || $CustomFieldAttribute->ui_type_id == $this->radioUi || $CustomFieldAttribute->ui_type_id == $this->multiSelectUi) {
                                $detail = CustomFieldAttribute::find($value['value']);
                                if ($detail) {
                                    $query->having($value['core_keys_id'] . '@@name', $detail->name);
                                }
                            } else {
                                $detail = CustomFieldAttribute::find($value['value']);
                                if ($detail) {
                                    $query->having($value['core_keys_id'], $detail->name);
                                }
                            }
                        }
                    }
                }
            }
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy($this->usrTableName . '.' . $this->userIdCol, $conds['order_type']);
            } elseif ($conds['order_by'] == 'role_id@@name') {
                $query->orderBy('role_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getUsers($relation = null, $status = null, $isBanned = null, $conds = null, $limit = null, $offset = null, $condsIn = null, $noPagination = null, $pagPerPage = null, $sort = null)
    {
        $sql = $this->getSqlForCustomField();

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $users = User::select($this->usrTableName . '.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'role_id@@name') {
                    $q->join($this->roleTableName, $this->roleTableName . '.' . $this->roleIdCol, '=', $this->userRoleIdCol);
                    $q->select($this->roleTableName . '.' . $this->roleNameCol . ' as role_name', $this->usrTableName . '.*');
                }
            })
            ->when($sql, function ($query, $sql) {
                $query->selectRaw($sql);
            })
            ->leftJoin($this->usrInfoTableName, $this->usrTableName . '.' . $this->userIdCol, '=', $this->usrInfoTableName . '.' . $this->usrInfoUserIdCol)
            ->leftJoin($this->customUiDetailTableName, $this->usrInfoTableName . '.' . $this->usrInfoValueCol, '=', $this->customUiDetailTableName . '.' . $this->customUiDetailIdCol)
            ->groupBy($this->usrTableName . '.' . $this->userIdCol)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($status, function ($q, $status) {
                $q->where($this->usrTableName . '.' . $this->userStatusCol, $status);
            })
            ->when($isBanned, function ($q, $isBanned) {
                $q->where($this->userIsBannedCol, $isBanned);
            })
            ->when($condsIn, function ($query) use ($condsIn) {
                if (isset($condsIn['ids'])) {
                    $query->whereIn($this->usrTableName . '.' . $this->userIdCol, $condsIn['ids']);
                }
                if (isset($condsIn['added_user_ids'])) {
                    $query->whereIn($this->usrTableName . '.' . $this->userAddedUserIdCol, $condsIn['added_user_ids']);
                }
            })
            ->when($limit, function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->usrTableName . '.' . $this->userNameCol, 'asc');
            })
            ->latest()
            ->orderBy('user_is_sys_admin', 'desc');
        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users->get();
        } else {
            $users = $users->get();
        }
        // dd($users, $offset);
        return $users;
    }

    public function getUser($id = null, $conds = null, $relation = null)
    {

        $user = User::when($id, function ($q, $id) {
            $q->where($this->userIdCol, $id);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->first();
        // dd($user);
        return $user;
    }

    //buyers

    public function getBuyers($relation = null, $status = null, $isBanned = null, $conds = null, $limit = null, $offset = null, $condsIn = null, $noPagination = null, $pagPerPage = null, $sort = null)
    {
        $sql = $this->getSqlForCustomField();

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $users = User::join('psx_user_boughts', 'psx_user_boughts.buyer_user_id', '=', 'users.id')
            ->select('users.*', DB::raw("count(psx_user_boughts.buyer_user_id) as purchased_item_count"))
            ->groupBy('buyer_user_id')
            ->when($sql, function ($query, $sql) {
                $query->selectRaw($sql);
            })
            ->leftJoin($this->usrInfoTableName, $this->usrTableName . '.' . $this->userIdCol, '=', $this->usrInfoTableName . '.' . $this->usrInfoUserIdCol)
            ->leftJoin('psx_customize_ui_details', $this->usrInfoTableName . '.value', '=', 'psx_customize_ui_details.id')
            ->groupBy($this->usrTableName . '.' . $this->userIdCol)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($status, function ($q, $status) {
                $q->where($this->userStatusCol, $status);
            })
            ->when($isBanned, function ($q, $isBanned) {
                $q->where($this->userIsBannedCol, $isBanned);
            })
            ->when($condsIn, function ($query, $condsIn) {
                if (isset($condsIn['ids']))
                    $query->whereIn($this->usrTableName . '.' . $this->userIdCol, $condsIn['ids']);

                if (isset($condsIn['added_user_ids']))
                    $query->whereIn($this->usrTableName . '.' . $this->userAddedUserIdCol, $condsIn['added_user_ids']);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->orderBy('overall_rating', 'desc')
            ->orderBy('purchased_item_count', 'desc')
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy('users.name', 'asc');
            });
        //        ->latest('psx_user_boughts.added_date', 'desc');

        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users->get();
        } else {
            $users = $users->get();
        }
        return $users;
    }

    //sellers
    public function getSellers($relation = null, $status = null, $isBanned = null, $conds = null, $limit = null, $offset = null, $condsIn = null, $noPagination = null, $pagPerPage = null, $sort = null)
    {
        $sql = $this->getSqlForCustomField();
        // $sort = '';
        // if(isset($conds['order_by'])){
        //     $sort = $conds['order_by'];
        // }

        $users = User::join('psx_items', 'psx_items.added_user_id', '=', 'users.id')
            ->select('users.*', DB::raw("count(psx_items.added_user_id) as purchased_item_count"))
            ->groupBy('added_user_id')
            ->when($sql, function ($query, $sql) {
                $query->selectRaw($sql);
            })
            ->leftJoin($this->usrInfoTableName, $this->usrTableName . '.' . $this->userIdCol, '=', $this->usrInfoTableName . '.' . $this->usrInfoUserIdCol)
            ->leftJoin('psx_customize_ui_details', $this->usrInfoTableName . '.value', '=', 'psx_customize_ui_details.id')
            ->groupBy($this->usrTableName . '.' . $this->userIdCol)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            // ->when($status, function ($q, $status){
            //     $q->where($this->userStatusCol, $status);
            // })
            ->when($isBanned, function ($q, $isBanned) {
                $q->where($this->userIsBannedCol, $isBanned);
            })
            ->when($condsIn, function ($query, $condsIn) {
                if (isset($condsIn['ids']))
                    $query->whereIn($this->usrTableName . '.' . $this->userIdCol, $condsIn['ids']);

                if (isset($condsIn['added_user_ids']))
                    $query->whereIn($this->usrTableName . '.' . $this->userAddedUserIdCol, $condsIn['added_user_ids']);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->orderBy('user_is_sys_admin', 'desc')->latest();

        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users->get();
        } else {
            $users = $users->get();
        }
        return $users;
    }

    // bannedUser
    public function indexBannedUser($request)
    {

        $code = $this->code;
        $customizeUis = CustomField::where('module_name', $code)->where('ui_type_id', 'uit00001')->latest()->get();
        $userRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();


        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, User::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['role_id'] = $request->input('role_filter') == 'all' ? null  : $request->role_filter;
        $conds['added_date'] = $request->input('date_filter') == 'all' ? null  : $request->date_filter;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 1;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation'
        ];

        $users = BannedUserWithKeyResource::collection($this->getUsers($userRelation, null, false, $conds, null, null, null, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption('banned_user');
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing user arr object with new format
        $changedUserObj = $users;
        $roles = $this->roleService->getRoles();

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['added_date'],
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['added_date'],
                'uis' => $uis,
            ];
        }
        return $dataArr;
    }

    public function editBannedUser($id)
    {
        $dataWithRelation = ["userRelation"];
        $user = $this->getUser($id, null, $dataWithRelation);
        $code = $this->code;

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();
        $roles = Role::all();
        $yourPermissions = UserPermission::where('user_id', $id)->first();

        $dataArr = [
            'user'   => $user,
            'roles'   => $roles,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];

        return $dataArr;
    }

    public function updateBannedUser($id, $request)
    {

        $user = $this->update($id, $request);
        return $user;
    }

    public function destroyBannedUser($id)
    {
        $user = $this->getUser($id);
        $name = $user->name;
        $user->delete();

        $status = [
            'flag' => $this->dangerFlag,
            'msg' => 'The ' . $name . ' row has been deleted successfully.'
        ];

        return $status;
    }

    public function ban($id)
    {
        $user = $this->getUser($id);

        if ($user->is_banned == $this->ban) {
            $user->is_banned = $this->unBan;
        } else {
            $user->is_banned = $this->ban;
        }
        $user->updated_user_id = Auth::user()->id;
        $user->update();
    }

    // ----------------custom fields----------------------------

    public function storeCustomFieldValues($request, $user, $coreKeysIds)
    {

        if (!empty($request->user_relation)) {
            foreach ($coreKeysIds as $coreKeysId) {
                foreach ($request->user_relation as $key => $value) {
                    if (is_array($value)) {
                        $coreKeysIdFromReq = $value['core_keys_id'];
                        $valueFromReq = $value['value'];
                    } else {
                        $coreKeysIdFromReq = $key;
                        $valueFromReq = $value;
                    }

                    if ($coreKeysIdFromReq === $coreKeysId) {
                        $userRelation = new UserInfo();

                        if (is_file($valueFromReq)) {
                            $file = $this->checkFileInCustomFieldValue($valueFromReq);
                        }

                        $userRelation->user_id = $user->id;

                        if ($valueFromReq === false) {
                            $userRelation->value = 0;
                        }
                        if (is_file($valueFromReq)) {
                            if (str_contains($valueFromReq->getMimeType(), 'image')) {
                                $userRelation->value = $file;
                            }
                        }
                        if (!is_file($valueFromReq) && $valueFromReq !== false) {
                            $userRelation->value = $valueFromReq;
                        }

                        $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
                        foreach ($customizeHeaders as $key2 => $customizeHeader) {
                            if ($coreKeysIdFromReq === $customizeHeader->core_keys_id) {
                                $userRelation->ui_type_id = $customizeHeader->ui_type_id;
                                $userRelation->core_keys_id = $customizeHeader->core_keys_id;
                            }
                        }

                        if (Auth::check()) {
                            $userRelation->added_user_id = Auth::id();
                        } else {
                            $userRelation->added_user_id = 0;
                        }

                        $userRelation->save();
                    }
                }
            }
        }
    }

    public function getCoreFieldFilteredLists($code)
    {
        return CoreField::where($this->coreFieldFilterModuleNameCol, $code)->get();
    }

    public function deleteCoreFilteredOldData($code)
    {
        $oldcoreFields = CoreField::with(["screenDisplayUiSetting"])->where($this->coreFieldFilterModuleNameCol, $code)->get();
        $oldcoreFieldIds = $oldcoreFields->pluck($this->coreFieldFilterIdCol);
        $screenDisplayUiSettingIds = DynamicColumnVisibility::whereIn($this->screenDisplayUiKeyCol, $oldcoreFieldIds)->get()->pluck($this->screenDisplayUiIdCol);

        // delete core field from screen_display_ui-settings
        DynamicColumnVisibility::destroy($screenDisplayUiSettingIds);

        // delete core field from core_fields
        //        foreach ($oldcoreFields as $oldcoreField) {
        //            $oldcoreField->delete();
        //        }
        CoreField::destroy($oldcoreFieldIds);
    }

    public function createCoreFieldsForFilter($userCoreLists, $labels, $coreFieldsForFilter, $roles, $users, $code, $coreFieldFilterForRelation)
    {
        foreach ($userCoreLists as $userCoreListKey => $userCoreList) {
            foreach ($coreFieldsForFilter as $coreFieldKey => $coreFieldForFilter) {
                if (($coreFieldForFilter !== null && $coreFieldForFilter !== false) && $coreFieldKey == $userCoreListKey) {

                    // save in core_field_filter_settings
                    $coreFieldFilterSetting = $this->saveCoreFieldFilterSettings($labels, $userCoreListKey, $coreFieldKey, $code, $userCoreList, $roles, $users, $coreFieldFilterForRelation);

                    // save in screen_display_ui_settings table
                    $this->saveScreenDisplayUiSettings($code, $coreFieldFilterSetting);
                }
            }
        }
    }

    public function saveCoreFieldFilterSettings($labels, $userCoreListKey, $coreFieldKey, $code, $userCoreList, $roles, $users, $coreFieldFilterForRelation)
    {
        $coreFieldFilterSetting = new CoreField();
        $coreFieldFilterSetting->module_name = $code;
        if ($userCoreListKey === $coreFieldKey) {
            if ($userCoreList == $this->userRoleIdCol) {
                $coreFieldFilterSetting->field_name = relationForCoreFieldFilter($userCoreList, $this->usrRoleIdCol, $roles, $this->roleNameCol, $coreFieldFilterForRelation);
            } elseif ($userCoreList == $this->userUserIdCol) {
                $coreFieldFilterSetting->field_name = relationForCoreFieldFilter($userCoreList, $this->usrUserIdCol, $users, $this->userNameCol, $coreFieldFilterForRelation);
            } else {
                $coreFieldFilterSetting->field_name = $userCoreList;
            }
        }

        foreach ($labels as $labelKey => $label) {
            if ($coreFieldKey === $labelKey) {
                $coreFieldFilterSetting->label_name = $label;
            }
        }

        $integerFields = ['user_is_sys_admin', 'status', 'is_banned', 'overall_rating', 'is_show_email', 'is_show_phone', 'is_shop_admin', 'is_city_admin', 'user_lat', 'user_lng'];
        $dateFields = ['added_date', 'updated_date'];
        if (in_array($coreFieldFilterSetting->field_name, $integerFields)) {
            $coreFieldFilterSetting->data_type = 'Integer';
        } elseif (in_array($coreFieldFilterSetting->field_name, $dateFields)) {
            $coreFieldFilterSetting->data_type = 'Date';
        } else {
            $coreFieldFilterSetting->data_type = 'String';
        }

        $coreFieldFilterSetting->save();

        return $coreFieldFilterSetting;
    }

    public function saveScreenDisplayUiSettings($code, $coreFieldFilterSetting)
    {
        $screenDisplayUiSetting = new DynamicColumnVisibility();
        $screenDisplayUiSetting->module_name = $code;
        $screenDisplayUiSetting->key = $coreFieldFilterSetting->id;
        $screenDisplayUiSetting->is_show = $this->show;
        $screenDisplayUiSetting->save();

        return $screenDisplayUiSetting;
    }

    public function makeColumnHideShown($request)
    {
        $hideShowFields = $request->value;
        //        foreach ($hideShowFields as $hideShowField){
        //            $DynamicColumnVisibility = DynamicColumnVisibility::find($hideShowField[$this->screenDisplayUiIdCol]);
        //            $DynamicColumnVisibility->is_show = $hideShowField['hidden'] ? $this->hide : $this->show;
        //            $DynamicColumnVisibility->update();
        //        }
        foreach ($hideShowFields as $hideShowField) {
            $hideShowFieldData[] = [
                'id' => $hideShowField['id'],
                'is_show' => $hideShowField['hidden'] ? $this->hide : $this->show,
            ];
        }
        DynamicColumnVisibility::upsert(
            $hideShowFieldData,
            ['id'],
            ['is_show']
        );
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiEnableCol, $this->enable)->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function getFilteredCoreFields($code, $limit = null, $offset = null, $isDel = null)
    {
        $coreFields = CoreField::when($code, function ($q, $code) {
            $q->where($this->coreFieldFilterModuleNameCol, $code);
        })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($isDel !== null,  function ($query) use ($isDel) {
                $query->where('is_delete', $isDel);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->get();
        return $coreFields;
    }

    public function getCustomizeFields($code = null, $dataWithRelation = null, $coreKeysId = null, $isDel = null, $limit = null, $offset = null)
    {
        $customizeHeader  =  CustomField::when($dataWithRelation, function ($q, $data) {
            $q->with($data);
        })
            ->when($code, function ($q, $data) {
                $q->where($this->customUiModuleName, $data);
            })
            ->when($isDel !== null,  function ($q) use ($isDel) {
                $q->where($this->customUiIsDelCol, $isDel);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($coreKeysId, function ($q, $data) {
                $q->where($this->customUiCoreKeysIdCol, $data);
            })
            ->get();
        return $customizeHeader;
    }

    public function getCustomizeField($code = null, $dataWithRelation = null, $coreKeysId = null)
    {
        $customizeHeader  =  CustomField::when($dataWithRelation, function ($q, $data) {
            $q->with($data);
        })
            ->when($code, function ($q, $data) {
                $q->where($this->coreFieldFilterModuleNameCol, $data);
            })
            ->when($coreKeysId, function ($q, $data) {
                $q->where($this->customUiCoreKeysIdCol, $data);
            })
            ->first();
        return $customizeHeader;
    }

    public function getCustomizeFieldAttrs($customizeHeaderId = null)
    {
        $customizeDetails = CustomFieldAttribute::when($customizeHeaderId, function ($q, $data) {
            $q->where($this->customUiCoreKeysIdCol, $data);
        })
            ->latest()->get();
        return $customizeDetails;
    }

    public function getCustomizeFieldAttr($id)
    {
        $customizeDetail = CustomFieldAttribute::find($id);
        return $customizeDetail;
    }

    public function deleteCustomizeFieldAttrs($customizeDetails)
    {
        if ($customizeDetails->isNotEmpty()) {
            $customizeDetailIds = $customizeDetails->pluck($this->customUiIdCol);
            CustomFieldAttribute::destroy($customizeDetailIds);
        }
    }

    public function filterForCoreField()
    {
        $userCoreLists = getAllCoreFields($this->usrTableName);
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($this->code);

        $dataArr = [
            'userCoreLists' => $userCoreLists,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

        return $dataArr;
    }

    public function filterForCoreFieldStore($request)
    {
        // delete old data start
        $this->deleteCoreFilteredOldData($this->code);
        // delete old data end

        // prepare for needing data start
        $userCoreLists = getAllCoreFields($this->usrTableName);
        $roles = getAllCoreFields($this->catTableName);
        $subCategories = getAllCoreFields($this->subCatTableName);
        $locationCities = getAllCoreFields($this->locationCityTableName);
        $locationTownships = getAllCoreFields($this->locationTownshipTableName);
        $users = getAllCoreFields($this->usrTableName);
        $currencies = getAllCoreFields($this->currencyTableName);
        $shops = getAllCoreFields($this->shopTableName);


        $code = $this->code;
        $coreFieldFilterForRelation = $this->coreFieldFilterForRelation;

        $coreFieldsForFilter = $request->coreFieldsForFilter;
        $labels = $request->label;
        // prepare for needing data end

        $this->createCoreFieldsForFilter($userCoreLists, $labels, $coreFieldsForFilter, $roles, $subCategories, $locationCities, $locationTownships, $users, $currencies, $shops, $code, $coreFieldFilterForRelation);
    }

    public function getValueByPluck($values, $pluckColumn)
    {
        $pluckedValues = $values->pluck($pluckColumn)->unique()->values();
        return $pluckedValues;
    }

    public function getValuesForCustomizeField($customizeHeaderIds = null, $userId = null)
    {
        $values = UserInfo::when($customizeHeaderIds, function ($q, $data) {
            $q->whereIn($this->usrInfoCoreKeysIdCol, $data);
        })
            ->when($userId, function ($q, $data) {
                $q->where($this->usrInfoUserIdCol, $data);
            })
            ->get();
        return $values;
    }

    public function getUserInfo($customizeHeaderId = null, $userId = null)
    {

        $value = UserInfo::when($customizeHeaderId, function ($q, $data) {
            $q->where($this->usrInfoCoreKeysIdCol, $data);
        })
            ->when($userId, function ($q, $data) {
                $q->where($this->usrInfoUserIdCol, $data);
            })
            ->first();
        return $value;
    }

    // api
    public function exists($conds)
    {
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

    public function indexFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_update_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            $offset = $request->offset;
            $limit = $request->limit;

            $userApiRelations = ['role', 'userRelation'];

            $users = UserApiResource::collection($this->getUsers($userApiRelations, true, null, $limit, $offset));

            if ($offset > 0 && $users->isEmpty()) {
                // no paginate data
                $data = [];
                return responseDataApi($data);
            } else if ($users->isEmpty()) {
                // no data db
                return responseMsgApi(__('core__api_no_data'), $this->noContentStatusCode, $this->successStatus);
            } else {
                return responseDataApi($users);
            }
        }
    }

    public function storeOrUpdateFromApi($request)
    {

        /// check permission start
        // $deviceToken = $request->header($this->deviceTokenParaApi);
        // $userId = $request->login_user_id;

        // // user token layer permission start
        // $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        // if (empty($userAccessApiToken)){
        //     $msg = __('core__api_update_no_permission',[],$request->language_symbol);
        //     return responseMsgApi($msg, $this->forbiddenStatusCode);
        // }else{
        DB::beginTransaction();

        try {
            if (isset($request->id)) {
                $id = $request->id;
                $user = $this->getUser($id);
                $request->role_id = $user->role_id;
            } else {
                $user = new User();
            }

            if (isset($request->code) && !empty($request->code))
                $user->code = $request->code;

            if (isset($request->status) && !empty($request->status))
                $user->status = $request->status;

            if (isset($request->role_id) && !empty($request->role_id)) {
                $user->role_id = $request->role_id;
            } else {
                $user->role_id = $this->normalUserRoleId;
            }

            if (isset($request->id)) {
                $user->update();
            } else {
                $user->save();
            }

            $this->storeOrUpdateUserPermission($user->id, $user->role_id);

            DB::commit();
            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
        }
        // }
    }

    public function createFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $code = $this->code;


        $customizeUiRelation = ['uiTypeId'];
        $custom = $this->getCustomizeFields($code, $customizeUiRelation, null, $this->unDelete, $limit, $offset);

        $coreFieldFilterSettings = $this->getFilteredCoreFields($code, $limit, $offset, 0);

        $coreFields = Schema::getColumnListing($this->usrTableName);

        $core = [];

        foreach ($coreFields as $coreField) {
            foreach ($coreFieldFilterSettings as $coreFieldFilterSetting) {
                if (str_contains($coreFieldFilterSetting->field_name, "@@")) {
                    $originFieldName = strstr($coreFieldFilterSetting->field_name, "@@", true);
                } else {
                    $originFieldName = $coreFieldFilterSetting->field_name;
                }

                if ($coreField == $originFieldName) {
                    // $coreFieldObj = new \stdClass(); ,[],$request->language_symbol

                    $coreFieldFilterSetting->placeholder = __($coreFieldFilterSetting->placeholder, [], $request->language_symbol);
                    $coreFieldFilterSetting->label_name = __($coreFieldFilterSetting->label_name, [], $request->language_symbol);


                    // $coreFieldObj->placeholder = isset($coreFieldFilterSetting->placeholder)?(string)$coreFieldFilterSetting->placeholder:'';
                    // $coreFieldObj->data_type = isset($coreFieldFilterSetting->data_type)?(string)$coreFieldFilterSetting->data_type:'';
                    // $coreFieldObj->is_core_field = isset($coreFieldFilterSetting->is_core_field)?(string)$coreFieldFilterSetting->is_core_field:'';
                    // $coreFieldObj->is_visible = isset($coreFieldFilterSetting->enable)?(string)$coreFieldFilterSetting->enable:'';
                    // $coreFieldObj->mandatory = isset($coreFieldFilterSetting->mandatory)?(string)$coreFieldFilterSetting->mandatory:'';
                    array_push($core, $coreFieldFilterSetting);
                }
            }
        }

        foreach ($custom as $customField) {

            $customField->placeholder = __($customField->placeholder, [], $request->language_symbol);
            $customField->name = __($customField->name, [], $request->language_symbol);
        }

        return [
            "custom" => $custom,
            "core" => $core
        ];
    }

    public function storeFromApi($request)
    {

        $user = $this->storeCoreFieldValues($request);

        if (isset($request->user_relation) && !empty($request->user_relation)) {
            // same from be
            $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
            $customizeHeaderIds = $this->getValueByPluck($customizeHeaders, $this->customUiCoreKeysIdCol);
            $this->storeCustomFieldValues($request, $user, $customizeHeaderIds);
            // same from be
        }

        $this->addFreeAdPostCount($user->id);

        // echo json_encode($request->all());exit;

        $user = new UserApiResource($this->getUser($user->id, null, $this->userApiRelations));
        return $user;
    }

    public function updateFromApi($request, $id)
    {

        // $deviceToken = $request->header($this->deviceTokenParaApi);
        // $userId = $request->login_user_id;

        // check permission start
        // $user = $this->getUser($id);
        // $checkPermission = checkPermissionApi($this->editAbility, $user);
        // $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($id, $deviceToken);
        // $checkOwnerShip = checkOwnerShip($user, $userId);

        // if (empty($userAccessApiToken) || !$checkOwnerShip){
        //     $msg = __('core__api_update_no_permission');
        //     return responseMsgApi($msg, $this->forbiddenStatusCode);
        // }

        // $user = $this->getUser($id);
        $request->role_id = $this->getUser($id)->role_id;
        // same from be
        $user = $this->updateCoreFieldValues($request);

        if (isset($request->user_relation) && !empty($request->user_relation)) {
            $data = $this->updateCustomFieldOldValues($request, $user);
            $newDataIndexs = array_diff(array_unique($data['allDataIndex']), array_unique($data['oldDataIndex']));
            $this->storeCustomFieldValues($request, $user, $newDataIndexs);
        }
        // same from be

        $user = new UserApiResource($this->getUser($user->id, null, $this->userApiRelations));
        return $user;
    }

    public function destroyFromApi($request)
    {
        //delete in users table
        $user = $this->getUser($request->user_id);

        if (!empty($user)) {

            // delete from user_access_api_token table start
            $userId[] = $request->user_id;

            $userAccessApiTokens = $this->userAccessApiTokenService->getUserAccessApiTokens($userId);
            $userAccessApiTokenIds = $userAccessApiTokens->pluck("id");

            if ($userAccessApiTokenIds->isNotEmpty()) {
                UserAccessApiToken::destroy($userAccessApiTokenIds);
            }
            // delete from user_access_api_token table end

            $user->delete();

            $images = $this->imageService->getImages($request->user_id, 'user');
            $this->imageService->deleteImagesFromBoth($images);

            //delete in user_infos table
            $userRelations = $this->getValuesForCustomizeField('', $user->id);
            $this->deleteCustomizeFieldData($userRelations);
        }

        if (empty($user)) {
            return responseMsgApi(__('core__api_record_not_found'), $this->noContentStatusCode);
        } else {
            $msg = __('core__api_user_delete_success', [], $request->language_symbol);
            return responseMsgApi($msg, $this->okStatusCode, $this->successStatus);
        }
    }


    public function loginFromApi($request)
    {

        $string = $request->email;
        $pattern = '/^\+\d/';
        $loginType = '';
        $userPhone = [];

        if (preg_match($pattern, $string, $matchesPhone)) {
            $firstDigit = $matchesPhone[0];
            // dd($firstDigit);
            $loginType = 'phone';
            $patternForPhone = '/\d{5}$/';

            if (preg_match($patternForPhone, $string, $matches)) {
                $lastFiveDigits = $matches[0];
                // dd($lastFiveDigits);
            } else {
                return responseMsgApi(__('core__api_record_not_found'), $this->badRequestStatusCode);
            }


            $userId = '';
            $userList = User::where(DB::raw("REPLACE(user_phone, '-', '')"), 'LIKE', '%' . $firstDigit . '%')->whereRaw("user_phone REGEXP '.$lastFiveDigits'")->get();
            // dd($userList);
            foreach ($userList as $key => $user) {
                $userPhone[$user->id] = str_replace("-", "", "$user->user_phone");
                // $userPhone['value'] =  str_replace("-", "", "$user->user_phone");
                // $userPhone['value'] = str_replace("-", "", "$user->user_phone");
                $userPhone += [$user->id => str_replace("-", "", "$user->user_phone")];
                if ($userPhone[$user->id] == $request->email) {
                    $userId = $user->id;
                }
            }

            // dd($userId);
        } else {
            $loginType = 'stringType';
        }



        if ($loginType == 'phone') {

            if (!empty($userList)) {
                if ($userId != '') {
                    $user = User::Where('id', $userId)->first();
                } else {
                    // return responseMsgApi(__('core__api_record_not_found'), $this->noContentStatusCode);
                    return responseMsgApi(__('core__api_record_not_found'), $this->badRequestStatusCode);
                }
            } else {
                return responseMsgApi(__('core__api_record_not_found'), $this->badRequestStatusCode);
            }
        } else {
            $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        }

        // $user = User::where('email', $request->email)->orWhere('username', $request->email)->orWhere('user_phone', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->is_banned) {
                return responseMsgApi(__('core__api_err_user_banned', [], $request->language_symbol), $this->badRequestStatusCode);
            } else {
                // save or update in user_access_api_tokens table start
                $userId = $user->id;
                $deviceId = $request->device_id;
                $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
                if (!$userAccessApiTokenStoreOrUpdate) {
                    return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
                }
                // save or update in user_access_api_tokens table end
                $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
                $user = new UserApiResource($user);
                return $user;
                // } else {
                //     return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
                // }
            }
        } else {
            return responseMsgApi(__('core__api_invalid_login', [], $request->language_symbol), $this->badRequestStatusCode);
        }
    }

    public function logout($request)
    {
        $userId = $request->id;
        $deviceId = $request->device_id;
        $headerToken = ps_constant::deviceTokenKeyFromApi;
        $deviceToken = $request->header($headerToken);
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken, $deviceId);
        if (!empty($userAccessApiToken)) {
            $userAccessApiToken->delete();
        }
        $pushNotificationToken = $this->pushNotificationTokenService->get(deviceToken: $deviceToken);
        if (!empty($pushNotificationToken)) {
            $pushNotificationToken->delete();
        }
        return true;
    }

    public function registerFromApi($request)
    {
        DB::beginTransaction();
        try {
            $email_verification_enabled = $this->backendSettingService->get()->email_verification_enabled;

            $user_data = $request;
            if ($email_verification_enabled != 1) {
                $user_data['status'] = $this->publish;
            } else {
                $code = generate_random_string(5);

                $user_data['status'] = $this->pending;
                $user_data['code'] = $code;
            }


            $user = $this->storeCoreFieldValues($user_data);

            // same from be
            if (isset($request->user_relation) && !empty($request->user_relation)) {
                $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
                $customizeHeaderIds = $this->getValueByPluck($customizeHeaders, $this->customUiCoreKeysIdCol);
                $this->storeCustomFieldValues($request, $user, $customizeHeaderIds);
            }
            // same from be

            if (isset($user['error'])) {
                return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
            } else {
                $user = $this->getUser($user->id, null, $this->userApiRelations);
                $this->addFreeAdPostCount($user->id);
            }


            // save or update push notification token
            $this->pushNotificationTokenService->storeOrUpdateNotiToken((array)$request->all(), $user->id);
            $user = new UserApiResource($user);
            // } else {
            //     return responseMsgApi('error', $this->internalServerErrorStatusCode);
            // }

            // save or update in user_access_api_tokens table start
            $userId = $user->id;
            $deviceId = $request->device_id;
            $headerToken = ps_constant::deviceTokenKeyFromApi;
            $userAccessApiTokenStoreOrUpdate = $this->userAccessApiTokenService->storeOrUpdate($request, $userId, $deviceId);
            // return responseMsgApi(__('core__api_db_error',[],$request->language_symbol), $this->internalServerErrorStatusCode);
            if (!$userAccessApiTokenStoreOrUpdate) {
                return responseMsgApi(__('core__api_db_error', [], $request->language_symbol), $this->internalServerErrorStatusCode);
            }
            // save or update in user_access_api_tokens table end

            // send mail
            $to = $request->email;
            $subject = __('core__api_new_user_register', [], $request->language_symbol);
            $to_name = $user->name;

            if ($email_verification_enabled != 1) {
                $title = __('core__api_new_user_register_title', [], $request->language_symbol);
                $body = __('core__api_new_user_acc', [], $request->language_symbol);
                // if (!sendMail($to, $to_name, $title, $subject, $body)) {
                //     return responseMsgApi('core__api_user_register_success_but_email_not_send', $this->internalServerErrorStatusCode);
                // }
                sendMail($to, $to_name, $title, $subject, $body);
            } else {
                $title = __('core__api_new_user_register_title', [], $request->language_symbol);
                $body =  __('core__api_new_user_acc_with_verify', [], $request->language_symbol);
                $subbody = __('core__api_verify_code', [], $request->language_symbol) . ': ' . $user->code;
                if (!sendMail($to, $to_name, $subject, $title, $body, $subbody)) {
                    return responseMsgApi(__('core__api_user_register_failed_due_to_email_not_send', [], $request->language_symbol), $this->internalServerErrorStatusCode);
                }
            }
            DB::commit();
            return $user;
        } catch(Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // for api
    public function searchFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        // $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        // if (empty($userAccessApiToken)){
        //     $msg = __('core__api_no_permission',[],$request->language_symbol);
        //     return responseMsgApi($msg, $this->forbiddenStatusCode);
        // }else{
        $offset = $request->offset;
        $limit = $request->limit;

        $conds['keyword'] = $request->keyword;
        $conds['order_by'] = $request->order_by;
        $conds['order_type'] = $request->order_type;

        // $userApiRelations = $this->userApiRelations;
        $users = UserApiResource::collection($this->getUsers(null, null, null, $conds, $limit, $offset));

        // save search history
        if (isset($request->keyword) && !empty($request->keyword)) {
            $searchdata = new \stdClass;
            $searchdata->user_id = $request->login_user_id;
            $searchdata->keyword = $request->keyword;
            $searchdata->type = $this->searchHistoryUserType;
            $searchdata->added_user_id = $request->login_user_id;
            $this->searchHistoryService->store($searchdata);
        }

        if ($offset > 0 && $users->isEmpty()) {
            // no paginate data
            $data = [];
            return responseDataApi($data);
        } else if ($users->isEmpty()) {
            // no data db
            return responseMsgApi(__('core__api_no_data'), $this->noContentStatusCode, $this->successStatus);
        } else {
            return responseDataApi($users);
        }
        // }
    }

    public function addFreeAdPostCount($userId)
    {
        $systemConfig = SystemConfig::first();
        $isPaidApp = $systemConfig->is_paid_app == 1 ? true : false;

        if ($isPaidApp && $systemConfig->free_ad_post_count != null) {
            $conds['user_id'] = $userId;
            $conds['core_keys_id'] = Constants::usrRemainingPost;
            $userInfo = UserInfo::where($conds)->first();
            if ($userInfo) {
                $userInfo->value = $systemConfig->free_ad_post_count;
                $userInfo->updated_user_id = $userId;
                $userInfo->update();
            } else {
                DB::beginTransaction();
                try {
                    $userInfo = new UserInfo();
                    $userInfo->core_keys_id = Constants::usrRemainingPost;
                    $userInfo->user_id = $userId;
                    $userInfo->value = $systemConfig->free_ad_post_count;
                    $userInfo->added_user_id = $userId;
                    $userInfo->save();

                    DB::commit();
                    return $userInfo;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return ['error' => $e->getMessage()];
                }
            }

            return $userInfo;
        }
    }

    public function getTopRatedSellerFromApi($request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $users = User::join('psx_user_boughts', 'psx_user_boughts.seller_user_id', '=', 'users.id')
            ->select('users.*')
            ->groupBy('seller_user_id')
            ->when($limit, function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->orderBy('overall_rating', 'desc')
            ->get();
        $topRatedSellers = UserApiResource::collection($users);
        if (!$topRatedSellers) {
            return responseMsgApi(__('core__api_no_data'), $this->noContentStatusCode, $this->successStatus);
        } else {
            return responseDataApi($topRatedSellers);
        }
    }

    public function getFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        $user_id = $request->login_user_id;

        $conds['id'] = $user_id;
        $user = new UserApiResource($this->getUser($user_id, $conds, $this->userApiRelations));

        if (!$user) {
            return responseMsgApi(__('core__api_no_data'), $this->noContentStatusCode, $this->successStatus);
        } else {
            return responseDataApi($user);
        }
    }

    public function verifyBlueMarkFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        $checkUserByLoginUser = checkUserByLoginUser($request->user_id, $userId);

        if (!$checkUserByLoginUser) {
            return responseMsgApi(__('core__api_no_permission', [], $request->language_symbol), $this->forbiddenStatusCode);
        }

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('blueMark__api_apply_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            $blueMark = $this->getUserInfo($this->usrIsVerifyBlueMark, $userId);
            $blueMarkNote = $this->getUserInfo($this->usrBlueMarkNote, $userId);

            $data['user_id'] = $request->user_id;

            if (BlueMarkUser::where($data)->count() == 0) {
                $data['added_user_id'] = $userId;
                BlueMarkUser::create($data);
            }
            if ($blueMark) {
                //if data already exist for blue mark
                if ($blueMark->value == NULL || $blueMark->value == 0 || $blueMark->value == 3) {
                    //update blue mark
                    $blueMark->value = '2';
                    $blueMark->update();

                    if ($blueMarkNote) {
                        //update note
                        $blueMarkNote->value = $request->note;
                        $blueMarkNote->update();
                    } else {
                        // save note
                        $saveNote = new UserInfo();
                        $saveNote->value = $request->note;
                        $saveNote->core_keys_id = $this->usrBlueMarkNote;
                        $saveNote->user_id = $userId;
                        $saveNote->ui_type_id = CustomField::where('core_keys_id', $this->usrBlueMarkNote)->first() ? CustomField::where('core_keys_id', $this->usrBlueMarkNote)->first()->ui_type_id : 'uit00006';
                        $saveNote->added_user_id = Auth::user()->id;
                        $saveNote->save();
                        $this->corekeyRelationService->store($saveNote);
                    }
                    return responseMsgApi(__('blueMark__api_blue_mark_success', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
                } else if ($blueMark->value == 2) {
                    $msg = __('blueMark__api_pending_blue_mark', [], $request->language_symbol);
                    return responseMsgApi($msg, $this->badRequestStatusCode);
                } else {
                    return responseMsgApi(__('blueMark__api_already_blue_mark', [], $request->language_symbol), $this->badRequestStatusCode);
                }
            } else {
                //if blue mark not exist , save
                $saveBlueMark = new UserInfo();
                $saveBlueMark->value = '2';
                $saveBlueMark->core_keys_id = $this->usrIsVerifyBlueMark;
                $saveBlueMark->user_id = $userId;
                $saveBlueMark->ui_type_id = CustomField::where('core_keys_id', $this->usrIsVerifyBlueMark)->first() ? CustomField::where('core_keys_id', $this->usrIsVerifyBlueMark)->first()->ui_type_id : 'uit00004';
                if (Auth::check()) {
                    $saveBlueMark->added_user_id = Auth::user()->id;
                } else {
                    $saveBlueMark->added_user_id = 0;
                }
                $saveBlueMark->save();
                $this->corekeyRelationService->store($saveBlueMark);

                if ($blueMarkNote) {
                    //update note
                    $blueMarkNote->value = $request->note;
                    $blueMarkNote->update();
                } else {
                    // save note
                    $saveNote = new UserInfo();
                    $saveNote->value = $request->note;
                    $saveNote->core_keys_id = $this->usrBlueMarkNote;
                    $saveNote->user_id = $userId;
                    $saveNote->ui_type_id = CustomField::where('core_keys_id', $this->usrBlueMarkNote)->first() ? CustomField::where('core_keys_id', $this->usrBlueMarkNote)->first()->ui_type_id : 'uit00006';
                    if (Auth::check()) {
                        $saveNote->added_user_id = Auth::user()->id;
                    } else {
                        $saveNote->added_user_id = 0;
                    }
                    $saveNote->save();
                    $this->corekeyRelationService->store($saveNote);
                }
                return responseMsgApi(__('blueMark__api_blue_mark_success', [], $request->language_symbol), $this->okStatusCode, $this->successStatus);
            }
        }
    }

    public function checkUsernameEmailPhone($value)
    {
        $user = User::where('email', $value)->orWhere('username', $value)->orWhere('user_phone', $value)->first();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    public function checkResetPasswordCode($request)
    {
        $conds['email'] = $request->email;

        $user = User::where($conds)->first();
        if ($user) {
            if (Hash::check($request->code, $user->password_reset_code)) {
                // $user->password_reset_code = Null;
                // $user->update();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //-----------------Report------------------------
    // user report
    public function userReportIndex($request)
    {

        $code = $this->code;
        $customizeUis = CustomField::where('module_name', $code)->where('ui_type_id', 'uit00001')->latest()->get();
        $userRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();


        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, User::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['role_id'] = $request->input('role_filter') == 'all' ? null  : $request->role_filter;
        $conds['date_range'] = $request->input('date_filter') == 'all' ? null  : $request->date_filter;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 0;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation'
        ];

        $users = UserReportWithKeyResource::collection($this->getUsers($userRelation, true, false, $conds, null, null, null, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption('user_report');
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing user arr object with new format
        $changedUserObj = $users;
        $roles = $this->roleService->getRoles();

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,
            ];
        }
        return $dataArr;
    }

    public function userReportShow($id, $relation)
    {
        $user = $this->getUser($id, null, $relation);
        $code = Constants::user;

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();

        $dataArr = [
            'user'   => $user,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];

        return $dataArr;
    }

    public function userReportCsvExport()
    {
        $filename = newFileNameForExport($this->userReportCsvFileName);
        return (new UserReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // buyer report
    public function buyerReportIndex($request)
    {
        $code = $this->code;
        $customizeUis = CustomField::where('module_name', $code)->where('ui_type_id', 'uit00001')->latest()->get();
        $uis = [];
        $userRelations = [];
        foreach ($customizeUis as $customizeUi) {

            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id', $customizeUi->core_keys_id)->get()->toArray();
        }

        $date_range = null;
        if (!empty($request->date_filter) && $request->date_filter != 'all') {
            $start_date = $request->date_filter[0];
            $end_date = $request->date_filter[1];
            if (empty($end_date)) {
                $end_date = Carbon::now();
            }
            $date_range = [$start_date, $end_date];
        }

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['date_range'] = $request->input('date_filter') == 'all' ? null  : $date_range;
        $conds['email'] = $request->input('email_filter') == 'all' ? null  : $request->email_filter;
        $conds['user_phone'] = $request->input('phone_filter') == 'all' ? null  : $request->phone_filter;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 0;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
            'blue_mark'
        ];

        $users = BuyerReportWithKeyResource::collection($this->getBuyers($userRelation, true, false, $conds, null, null, null, false, $row));

        //for email and phone filter
        // $emails = User::select($this->userIdCol, $this->userEmailCol)->whereNotNull($this->userEmailCol)->groupBy($this->userEmailCol)->get();
        // $phones = User::select($this->userIdCol, $this->userPhoneCol)->whereNotNull($this->userPhoneCol)->groupBy($this->userPhoneCol)->get();

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing user arr object with new format
        $changedUserObj = $users;

        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,

            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['date_range'],
                'uis' => $uis,

            ];
        }
        return $dataArr;
    }

    public function buyerReportShow($id, $relation)
    {
        $user = $this->getUser($id, null, $relation);
        $code = $this->code;

        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();

        $dataArr = [
            'user'   => $user,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
        ];

        return $dataArr;
    }

    public function buyerReportCsvExport()
    {
        $filename = newFileNameForExport($this->buyerReportCsvFileName);
        return (new BuyerReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // seller report
    public function sellerReportIndex($request)
    {
        $code = $this->code;
        $customizeUis = CustomField::where('module_name', $code)->where('ui_type_id', 'uit00001')->latest()->get();
        $uis = [];
        $userRelations = [];
        foreach ($customizeUis as $customizeUi) {

            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id', $customizeUi->core_keys_id)->get()->toArray();
        }
        $date_range = null;
        if (!empty($request->date_filter) && $request->date_filter != 'all') {
            $start_date = $request->date_filter[0];
            $end_date = $request->date_filter[1];
            if (empty($end_date)) {
                $end_date = Carbon::now();
            }
            $date_range = [$start_date, $end_date];
        }
        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['date_range'] = $request->input('date_filter') == 'all' ? null  : $date_range;
        // $conds['email'] = $request->input('email_filter') == 'all'? null  : $request->email_filter;
        // $conds['user_phone'] = $request->input('phone_filter') == 'all'? null  : $request->phone_filter;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 0;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
            'blue_mark'
        ];

        $users = SellerReportWithKeyResource::collection($this->getSellers($userRelation, true, false, $conds, null, null, null, false, $row));


        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];


        // $emails = User::select($this->userIdCol, $this->userEmailCol)->whereNotNull($this->userEmailCol)->groupBy($this->userEmailCol)->get();
        // $phones = User::select($this->userIdCol, $this->userPhoneCol)->whereNotNull($this->userPhoneCol)->groupBy($this->userPhoneCol)->get();

        // changing user arr object with new format
        $changedUserObj = $users;

        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['date_range'],
                'usrIsVerifyBlueMark' => $this->usrIsVerifyBlueMark,
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['date_range'],
                'usrIsVerifyBlueMark' => $this->usrIsVerifyBlueMark,
                'uis' => $uis,
            ];
        }
        return $dataArr;
    }

    public function sellerReportShow($id, $relation)
    {
        $user = $this->getUser($id, null, $relation);
        $code = $this->code;

        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();

        $dataArr = [
            'user'   => $user,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
        ];

        return $dataArr;
    }

    public function sellerReportCsvExport()
    {
        $filename = newFileNameForExport($this->sellerReportCsvFileName);
        return (new SellerReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // daily active user report
    public function dailyActiveUserReportIndex($request)
    {

        $code = $this->code;
        $customizeUis = CustomField::where('module_name', $code)->where('ui_type_id', 'uit00001')->latest()->get();
        $userRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();


        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, User::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['role_id'] = $request->input('role_filter') == 'all' ? null  : $request->role_filter;
        $conds['added_date'] = $request->input('date_filter') == 'all' ? null  : $request->date_filter;

        if (!empty($userRelations)) {
            $conds['user_relation'] = $userRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['is_banned'] = 0;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $userRelation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation'
        ];

        $users = DailyActiveUserReportWithKeyResource::collection($this->getUsers($userRelation, true, false, $conds, null, null, null, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showUserCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing user arr object with new format
        $changedUserObj = $users;
        $roles = $this->roleService->getRoles();

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['added_date'],
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'roles' => $roles,
                'users' => $changedUserObj,
                'search' => $conds['searchterm'],
                'selectedRole' => $conds['role_id'],
                'selectedDate' => $conds['added_date'],
                'uis' => $uis,
            ];
        }
        return $dataArr;
    }

    public function dailyActiveUserReportShow($id, $relation)
    {
        $user = $this->getUser($id, null, $relation);
        $code = $this->code;

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->getCustomizeFieldAttrs();

        $dataArr = [
            'user'   => $user,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];

        return $dataArr;
    }

    public function dailyActiveUserReportCsvExport()
    {
        $filename = newFileNameForExport($this->dailyActiveUserReportCsvFileName);
        return (new DailyActiveUserReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
