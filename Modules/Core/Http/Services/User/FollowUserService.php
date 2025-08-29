<?php

namespace Modules\Core\Http\Services\User;

use App\Models\User;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\User\FollowUser;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\User\UserInfoServiceInterface;
use App\Http\Contracts\User\FollowUserServiceInterface;

class FollowUserService extends PsService implements FollowUserServiceInterface
{
    public function __construct(
        protected UserInfoServiceInterface $uesrInfoService,
        protected UserServiceInterface $userService)
    {

    }
    public function save($followUserData)
    {
        DB::beginTransaction();
        try{
            $alreadyFollowed = $this->get(null, $followUserData);
            if($alreadyFollowed){
                // unfollow user
                $this->deleteFollowUser($alreadyFollowed->id);
            }else{
                // save follow user
                $this->saveFollowUser($followUserData);
            }

            // save or update followed user info
            $this->createOrUpdateUserInfo($followUserData['followed_user_id']);

            // save or udpate follow by user info
            $this->createOrUpdateUserInfo($followUserData['user_id']);

            DB::commit();

            return $this->userService->get($followUserData['followed_user_id'], null, ['userRelation']);
        }catch(\Throwable $e){
            DB::rollback();
            throw $e;
        }
    }

    public function get($id = null, $conds = null)
    {
        return FollowUser::when($id, function($query, $id){
            $query->where(FollowUser::id, $id);
        })
        ->when($conds, function($query, $conds){
            $query->where($conds);
        })
        ->first();
    }

    public function getAll($userId, $relation = null, $conds = null, $limit = null, $offset = null)
    {
        if($conds['return_types'] == Constants::followingReturnType){
            $userColumn = FollowUser::followedUserId;
            $followUserColumn = FollowUser::userId;
        }else{
            $userColumn = FollowUser::userId;
            $followUserColumn = FollowUser::followedUserId;
        }

        return FollowUser::select(User::tableName.'.*')
            ->join(User::tableName, FollowUser::t($userColumn), User::t(User::id))
            ->when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->when($userId, function ($query, $userId) use($followUserColumn) {
                $query->where(FollowUser::t($followUserColumn), $userId);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
    }

    ////////////////////////////////////////////////////////////////////
    // Private Functions
    ////////////////////////////////////////////////////////////////////

    //------------------------------------------------------------------
    // Data Preparation
    //------------------------------------------------------------------
    private function prepareGetAllFollowUsers($returnType)
    {
        return [
            'return_types' => $returnType
        ];
    }

    //------------------------------------------------------------------
    // Database
    //------------------------------------------------------------------
    private function saveFollowUser($followUserData)
    {
        $followUser = new FollowUser();
        $followUser->fill($followUserData);
        $followUser->added_user_id = Auth::id();
        $followUser->save();

        return $followUser;
    }

    private function deleteFollowUser($id)
    {
        $followUser = $this->get($id);
        $followUser->delete();
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['user_name']) && $conds['user_name']) {
            $search = $conds['user_name'];
            $query->where(function ($query) use ($search) {
                $query->where(User::t(User::name), 'like', '%' . $search . '%')
                    ->orWhere(User::t(User::email), 'like', '%' . $search . '%');
            });
        }

        $query->when(isset($conds['overall_rating']), function($query) use($conds) {
            $query->where(User::t(User::overallRating), $conds['overall_rating']);
        });

        return $query;
    }

    //------------------------------------------------------------------
    // Database
    //------------------------------------------------------------------
    private function createOrUpdateUserInfo($parentId)
    {
        // follower count
        $followerConds = $this->prepareGetAllFollowUsers(Constants::followerReturnType);
        $followerCount = $this->getAll($parentId, null, $followerConds)->count();

        // following count
        $followingConds = $this->prepareGetAllFollowUsers(Constants::followingReturnType);
        $followingCount = $this->getAll($parentId, null, $followingConds)->count();

        $customFieldValues = [
            Constants::usrFollowerCount => $followerCount,
            Constants::usrFollowingCount => $followingCount
        ];

        $this->uesrInfoService->update($parentId, $customFieldValues);
    }
}
