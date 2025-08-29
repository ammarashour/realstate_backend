<?php

namespace Modules\Core\Http\Services\User;

use App\Http\Contracts\Core\PsInfoServiceInterface;
use App\Http\Contracts\User\UserInfoServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\UserInfo;

class UserInfoService extends PsService implements UserInfoServiceInterface
{
    public function __construct(
        protected PsInfoServiceInterface $psInfoService,
        protected CustomFieldServiceInterface $customFieldServiceInterface) {}

    public function save($parentId, $customFieldValues)
    {
        $this->psInfoService->save(Constants::user, $customFieldValues, $parentId, UserInfo::class, 'user_id');
    }

    public function update($parentId, $customFieldValues)
    {
        // $this->deleteAll($parentId);
        // $this->psInfoService->update(Constants::user, $customFieldValues, $parentId, UserInfo::class, 'user_id');

        // $coreKeysIds = array_keys($customFieldValues);
        // $getOldInfoValues = $this->getAll($coreKeysIds, $parentId, null, Constants::yes);

        $this->psInfoService->update(Constants::user, $customFieldValues, $parentId, UserInfo::class, 'user_id');
        // $this->deleteAll($getOldInfoValues);
    }

    public function deleteAll($customFieldValues)
    {
        $this->psInfoService->deleteAll($customFieldValues);
        // $userInfos = $this->getAll(null, $parentId, null, Constants::yes);
        // foreach ($userInfos as $userInfo) {
        //     $userInfo->delete();
        // }
    }

    public function get($id = null, $relation = null, $parentId = null, $coreKeysId = null)
    {
        return UserInfo::when($id, function ($q, $id) {
            $q->where(UserInfo::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })
            ->when($parentId && $coreKeysId, function ($q) use($parentId, $coreKeysId) {
                $q->where([
                    UserInfo::userId => $parentId,
                    UserInfo::coreKeysId => $coreKeysId
                ]);
            })
            ->first();
    }

    public function getAll($coreKeysIds = null, $parentId = null, $relation = null, $noPagination = null, $pagPerPage = null)
    {
        $userInfos = UserInfo::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($coreKeysIds, function ($q, $coreKeysIds) {
                $q->whereIn(UserInfo::coreKeysId, $coreKeysIds);
            })
            ->when($parentId, function ($q, $parentId) {
                $q->where(UserInfo::userId, $parentId);
            });
        if ($pagPerPage) {
            return $userInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $userInfos->get();
        }

    }
}
