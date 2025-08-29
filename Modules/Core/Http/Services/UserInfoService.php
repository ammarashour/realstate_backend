<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_config;
use App\Http\Services\PsService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\UserInfo;
use Carbon\Carbon;

class UserInfoService extends PsService
{
    protected $userIdCol, $usrIsVerifyBlueMark, $usrBlueMarkNote, $userService, $userInfoIdCol, $userInfoCoreKeysIdCol, $userInfoUserIdCol, $userInfoValueCol;

    public function __construct(UserService $userService)
    {
        $this->userInfoIdCol = UserInfo::id;
        $this->userInfoCoreKeysIdCol = UserInfo::coreKeysId;
        $this->userInfoUserIdCol = UserInfo::userId;
        $this->userInfoValueCol = UserInfo::value;

        $this->usrIsVerifyBlueMark = Constants::usrIsVerifyBlueMark;
        $this->usrBlueMarkNote = Constants::usrBlueMarkNote;

        $this->code = Constants::user;

        $this->usrInfoUserIdCol = UserInfo::userId;
        $this->usrInfoCoreKeysIdCol = UserInfo::coreKeysId;
        $this->userInfoTableName = UserInfo::tableName;
        $this->userInfoUpdatedDateCol = UserInfo::updatedDate;

        $this->paginationPerPage = ps_config::pagPerPage;

        $this->componentName = "userInfo";

        $this->userTableName = User::tableName;
        $this->userNameCol = User::name;
        $this->userStatusCol = User::status;
        $this->userIsBannedCol = User::isBanned;
        $this->userIdCol = User::id;


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

        $this->userService = $userService;

        $this->userApiRelations = ['userRelation'];
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $userInfo = new UserInfo();

            if (isset($request->core_keys_id) && !empty($request->core_keys_id))
                $userInfo->core_keys_id = $request->core_keys_id;

            if (isset($request->user_id) && !empty($request->user_id))
                $userInfo->user_id = $request->user_id;

            if (isset($request->value) && !empty($request->value))
                $userInfo->value = $request->value;

            if (isset($request->ui_type_id) && !empty($request->ui_type_id)) {
                $userInfo->ui_type_id = $request->ui_type_id;
            }

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $userInfo->added_user_id = $request->added_user_id;
            else
                $userInfo->added_user_id = Auth::user()->id;

            $userInfo->save();

            DB::commit();
            return $userInfo;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $userInfo = $this->getUserInfo($id);

            if (isset($request->core_keys_id) && !empty($request->core_keys_id))
                $userInfo->core_keys_id = $request->core_keys_id;

            if (isset($request->user_id) && !empty($request->user_id))
                $userInfo->user_id = $request->user_id;

            if (isset($request->value))
                $userInfo->value = $request->value;

            if (isset($request->ui_type_id) && !empty($request->ui_type_id)) {
                $userInfo->ui_type_id = $request->ui_type_id;
            }

            if (isset($request->updated_user_id) && !empty($request->updated_user_id))
                $userInfo->updated_user_id = $request->updated_user_id;
            else
                $userInfo->updated_user_id = Auth::user()->id;

            $userInfo->update();

            DB::commit();
            return $userInfo;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->userNameCol, 'like', '%' . $search . '%');
            });
        }

        // keyword
        if (isset($conds['keyword']) && $conds['keyword']) {
            $search = $conds['keyword'];
            $query->where(function ($query) use ($search) {
                $query->where($this->userNameCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['role_id']) && $conds['role_id']) {
            $role_filter = $conds['role_id'];
            $query->whereHas('role', function ($q) use ($role_filter) {
                $q->where($this->userTableName . '.' . $this->userRoleIdCol, $role_filter);
            });
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where($this->userTableName . '.' . $this->userAddedDateCol, $date_filter);
            });
        }
        if (isset($conds['date_range']) && $conds['date_range']) {
            $date_filter = $conds['date_range'];
            if ($date_filter[1] == '') {
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween('users.added_date', $date_filter);
        }

        if (isset($conds['status'])) {
            $query->where($this->userTableName . '.' . $this->userStatusCol, $conds['status']);
        }

        if (isset($conds['is_banned'])) {
            $query->where($this->userTableName . '.' . $this->userIsBannedCol, $conds['is_banned']);
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->userTableName . '.' . $this->userAddedUserIdCol, $conds['added_user_id']);
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
                $query->orderBy('users.id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'role_id@@name') {
                $query->orderBy('role_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getBlueMarkUsers($conds = null, $noPagination = null, $pagPerPage = null)
    {
        // echo json_encode($conds);exit;


        $users = User::join($this->userInfoTableName, $this->userTableName . '.' . $this->userIdCol, '=', $this->userInfoTableName . '.' . $this->userInfoUserIdCol)
            ->with(['user_blue_mark'])
            ->where($this->userInfoTableName . '.' . $this->userInfoCoreKeysIdCol, 'ps-usr00002')
            ->when($conds, function ($query, $conds) {
                if (isset($conds['bluemark_status'])) {
                    $query->where($this->userInfoTableName . '.' . $this->userInfoValueCol, $conds['bluemark_status']);
                }
                if (isset($conds['bluemark_updated_at'])) {


                    // $date_filter=$conds['bluemark_updated_at'];
                    // $new_date=date('Y-m-d', strtotime($date_filter));
                    // // echo json_encode($new_date);exit;
                    // $query->whereDate('psx_user_infos.updated_date', $new_date);

                    $date_filter = $conds['bluemark_updated_at'];
                    // dd($date_filter);
                    if ($date_filter[1] == '') {
                        $date_filter[1] = Carbon::now();
                    }
                    $query->whereBetween($this->userInfoTableName . '.' . $this->userInfoUpdatedDateCol, $date_filter);
                }

                $query = $this->searching($query, $conds);
            })
            ->select([$this->userTableName . '.*', $this->userInfoTableName . '.' . $this->userInfoUpdatedDateCol . ' as bluemark_updated_at']);

        if ($pagPerPage) {
            $users = $users->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $users = $users - get();
        }
        // dd($users);
        return $users;
    }

    public function getUserInfos($relations = null, $limit = null, $offset = null, $conds = null)
    {
        $userInfos = UserInfo::when($relations, function ($query, $relations) {
            $query->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $userInfos;
    }

    public function getUserInfo($id = null, $relations = null, $core_keys_id = null, $conds = null)
    {
        $userInfo = UserInfo::when($id, function ($query, $id) {
            $query->where($this->userInfoIdCol, $id);
        })
            ->when($core_keys_id, function ($query, $core_keys_id) {
                $query->where($this->userInfoCoreKeysIdCol, $core_keys_id);
            })
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $userInfo;
    }
}
