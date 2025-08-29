<?php

namespace Modules\Core\Http\Services\User;

use Carbon\Carbon;
use App\Models\User;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\Role;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User\UserBought;
use Modules\Core\Constants\Constants;
use Modules\Core\Exports\BuyerReportExport;
use Modules\Core\Exports\SellerReportExport;
use Modules\Core\Entities\Utilities\CustomField;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\User\UserInfoServiceInterface;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use App\Http\Contracts\Image\ImageProcessingServiceInterface;
use App\Http\Contracts\Authorization\UserPermissionServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;

class UserService extends PsService implements UserServiceInterface
{
    public function __construct(
        protected ImageServiceInterface $imageService,
        protected ImageProcessingServiceInterface $imageProcessingService,
        protected UserInfoServiceInterface $userInfoService,
        protected UserPermissionServiceInterface $userPermissionService,
        protected SystemConfigServiceInterface $systemConfigService
    ) {}

    public function save($userData, $userCoverPhoto, $relationalData = [])
    {
        DB::beginTransaction();

        try {
            // save profile photo
            $userData['user_cover_photo'] = $this->saveProfilePhoto(null, $userCoverPhoto);

            // save user
            $user = $this->saveUser($userData);

            // save user info custom fields
            $this->userInfoService->save($user->id, $relationalData);

            // add user permission
            $userPermissionData = $this->prepareUserPermissionData($user);
            $this->userPermissionService->save($userPermissionData);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $userData, $userCoverPhoto, $relationalData = [])
    {
        DB::beginTransaction();

        try {
            // save profile photo
            if (empty($userCoverPhoto)) {
                unset($userData['user_cover_photo']);
            } else {
                $userData['user_cover_photo'] = $this->saveProfilePhoto($id, $userCoverPhoto);
            }
            // hash password
            $preUserData = $this->prepareUpdateUserData($userData);

            // update user
            $user = $this->updateUser($id, $preUserData);

            // update user info custom fields
            $this->userInfoService->update($user->id, $relationalData);

            // update user permission
            $userPermissionData = $this->prepareUserPermissionData($user);
            $this->userPermissionService->update(null, $user->id, $userPermissionData);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $name = $this->deleteUser($id);

            // delete user info
            $userRelations = $this->userInfoService->getAll(null, $id, null, Constants::yes, null);

            $this->userInfoService->deleteAll($userRelations);

            // delete user permission
            $this->userPermissionService->delete(null, $id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => __($name)]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relation = null)
    {

        return User::when($id, function ($query, $id) {
            $query->where(User::id, $id);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->first();
    }

    public function getAll($relation = null, $status = null, $isBanned = null, $conds = null, $limit = null, $offset = null, $condsIn = null, $noPagination = null, $pagPerPage = null, $sort = null, $report = null, $isTopRatedSeller = null)
    {
        $sql = getSqlForCustomField(Constants::user);

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $users = User::select(User::tableName . '.*')
            ->when(isset($report), function ($q) use ($report) {
                if ($report == 'buyer_report') {
                    $q->join(UserBought::tableName, UserBought::tableName . '.' . UserBought::buyerUserId, '=', User::tableName . '.' . User::id);
                    $q->select(User::tableName . '.*', DB::raw("count(psx_user_boughts.buyer_user_id) as purchased_item_count"));
                    $q->groupBy('buyer_user_id');
                }
                if ($report == 'seller_report') {
                    $q->join(Item::tableName, Item::tableName . '.' . Item::addedUserId, '=', User::tableName . '.' . User::id);
                    $q->select(User::tableName . '.*', DB::raw("count(case when psx_items.is_sold_out = 1 then psx_items.added_user_id end) as purchased_item_count"));
                    $q->groupBy('added_user_id');
                }
            })
            ->when($isTopRatedSeller, function ($q) use ($isTopRatedSeller) {

                $q->join(UserBought::tableName, UserBought::tableName . '.' . UserBought::sellerUserId, '=', User::tableName . '.' . User::id)
                    ->select(User::tableName . '.*')
                    ->groupBy(UserBought::sellerUserId)
                    ->orderBy(User::overallRating, Constants::descending);
            })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'role_id@@name') {
                    $q->join(Role::tableName, Role::tableName . '.' . Role::id, '=', User::roleId);
                    $q->select(Role::tableName . '.' . Role::name . ' as role_name', User::tableName . '.*');
                }
            })
            ->when($sql, function ($query, $sql) {
                $query->selectRaw($sql);
            })
            ->leftJoin(UserInfo::tableName, User::tableName . '.' . User::id, '=', UserInfo::tableName . '.' . UserInfo::userId)
            ->leftJoin(CustomFieldAttribute::tableName, UserInfo::tableName . '.' . UserInfo::value, '=', CustomFieldAttribute::tableName . '.' . CustomFieldAttribute::id)
            ->groupBy(User::tableName . '.' . User::id)
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($status, function ($q, $status) {
                $q->where(User::tableName . '.' . User::status, $status);
            })
            ->when($isBanned, function ($q, $isBanned) {
                $q->where(User::isBanned, $isBanned);
            })
            ->when($condsIn, function ($query) use ($condsIn) {
                if (isset($condsIn['ids'])) {
                    $query->whereIn(User::tableName . '.' . User::id, $condsIn['ids']);
                }
                if (isset($condsIn['added_user_ids'])) {
                    $query->whereIn(User::tableName . '.' . User::addedUser, $condsIn['added_user_ids']);
                }
            })
            ->when($limit, function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort) && empty($isTopRatedSeller), function ($query, $conds) {
                $query->orderBy(User::tableName . '.' . User::name, 'asc');
            });
        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users->get();
        } else {
            $users = $users->get();
        }

        return $users;
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);

            return $this->updateUser($id, $status);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function ban($id, $ban)
    {
        try {
            $banData = $this->prepareUpdateBanData($ban);

            return $this->updateUser($id, $banData);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function replaceImage($id, $userCoverPhoto)
    {
        DB::beginTransaction();
        try {
            $profilePhoto = $this->saveProfilePhoto($id, $userCoverPhoto);

            $userData = $this->prepareUpdateCoverPhoto($profilePhoto);
            $this->updateUser($id, $userData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteImage($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->get($id);
            $this->imageProcessingService->deleteImageFile($user->user_cover_photo);

            $userData = $this->prepareUpdateCoverPhoto(null);
            $this->updateUser($id, $userData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reportCsvExport($reportName, $reportExportClass)
    {
        $filename = newFileNameForExport($reportName);
        return (new $reportExportClass)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function addFreeAdPostCount($userId)
    {
        $systemConfig = $this->systemConfigService->get();
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
                    throw $e;
                }
            }

            return $userInfo;
        }
    }

    //////////////////////////////////////////////////////////////////////
    /// Private Functions
    //////////////////////////////////////////////////////////////////////

    ///-------------------------------------------------------------------
    /// Data Preparation
    ///-------------------------------------------------------------------
    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }

    private function prepareUpdateBanData($ban)
    {
        return ['is_banned' => $ban];
    }

    private function prepareUpdateUserData($userData)
    {
        if (array_key_exists('password', $userData) && $userData['password'] == null) {
            unset($userData['password']);
        }
        if (array_key_exists('password', $userData) && $userData['password'] != null) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return $userData;
    }

    private function prepareUpdateCoverPhoto($fileName)
    {
        return ['user_cover_photo' => $fileName];
    }

    private function prepareUserPermissionData($user)
    {
        $userPermission = new \stdClass();
        $userPermission->user_id = $user->id;
        $userPermission->role_id = $user->role_id;

        return $userPermission;
    }

    ///-------------------------------------------------------------------
    /// Database
    ///-------------------------------------------------------------------
    private function saveUser($userData)
    {
        $user = new User();
        $user->fill($userData);
        $user->password = Hash::make($userData['password']);
        $user->added_date_timestamp = strtotime(Carbon::now());
        $user->added_user_id = Auth::user()->id;
        $user->save();

        return $user;
    }

    private function updateUser($id, $userData)
    {
        $user = $this->get($id);
        $user->updated_user_id = Auth::user()->id;
        $user->update($userData);

        return $user;
    }

    private function deleteUser($id)
    {
        $user = $this->get($id);
        $name = $user->name;
        $this->imageProcessingService->deleteImageFile($user->user_cover_photo);
        $user->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(User::t(User::name), 'like', '%' . $search . '%')
                    ->orWhere(User::t(User::email), 'like', '%' . $search . '%')
                    ->orWhere(User::t(User::userPhone), 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['date_range']) && $conds['date_range']) {
            $date_filter = $conds['date_range'];
            if ($date_filter[1] == '') {
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween(User::t(User::addedDate), $date_filter);
        }

        $query->when(isset($conds['keyword']), function ($query) use ($conds) {
            $query->where(User::t(User::name), 'like', '%' . $conds['keyword'] . '%');
        })
            ->when(isset($conds['email']), function ($query) use ($conds) {
                $query->where(User::t(User::email), $conds['email']);
            })
            ->when(isset($conds['user_phone']), function ($query) use ($conds) {
                $query->where(User::t(User::userPhone), $conds['user_phone']);
            })
            ->when(isset($conds['role_id']), function ($query) use ($conds) {
                $query->where(User::t(User::roleId), $conds['role_id']);
            })
            ->when(isset($conds['added_date']), function ($query) use ($conds) {
                $query->where(User::t(User::addedDate), $conds['added_date']);
            })
            ->when(isset($conds['status']), function ($query) use ($conds) {
                $query->where(User::t(User::status), $conds['status']);
            })
            ->when(isset($conds['is_banned']), function ($query) use ($conds) {
                $query->where(User::t(User::isBanned), $conds['is_banned']);
            })
            ->when(isset($conds['added_user_id']), function ($query) use ($conds) {
                $query->where(User::t(User::addedUser), $conds['added_user_id']);
            });

        if (isset($conds['user_relation']) && ! empty($conds['user_relation'])) {
            $customizeUis = CustomField::where('module_name', Constants::user)->latest()->get();
            foreach ($conds['user_relation'] as $key => $value) {

                if (! empty($value['value'])) {
                    foreach ($customizeUis as $CustomFieldAttribute) {
                        if ($value['core_keys_id'] == $CustomFieldAttribute->core_keys_id) {
                            if ($CustomFieldAttribute->ui_type_id == Constants::dropDownUi || $CustomFieldAttribute->ui_type_id == Constants::radioUi || $CustomFieldAttribute->ui_type_id == Constants::multiSelectUi) {
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
        $query->when(isset($conds['order_by']) && isset($conds['order_type']), function ($query) use ($conds) {
            $query->when($conds['order_by'] == 'id', function ($query) use ($conds) {
                $query->orderBy(User::t(User::id), $conds['order_type']);
            })
                ->when($conds['order_by'] == 'role_id@@name', function ($query) use ($conds) {
                    $query->orderBy('role_name', $conds['order_type']);
                })
                ->when($conds['order_by'] != 'id' && $conds['order_by'] != 'role_id@@name', function ($query) use ($conds) {
                    $query->orderBy($conds['order_by'], $conds['order_type']);
                });
        });

        return $query;
    }

    private function saveProfilePhoto($userId = null, $userCoverPhoto)
    {
        if ($userId != null) {
            $user = $this->get($userId);
            $this->imageProcessingService->deleteImageFile($user->user_cover_photo);
        }

        $fileName = newFileName($userCoverPhoto);

        $resolutions = ['1x', '2x', '3x', 'original'];
        $this->imageProcessingService->createImageFiles(
            file: $userCoverPhoto,
            fileName: $fileName,
            imageType: 'profile',
            resolutions: $resolutions
        );

        return $fileName;
    }
}
