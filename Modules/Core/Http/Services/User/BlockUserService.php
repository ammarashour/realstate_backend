<?php

namespace Modules\Core\Http\Services\User;

use App\Config\Cache\ItemCache;
use App\Models\User;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\User\BlockUser;
use App\Http\Contracts\User\BlockUserServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class BlockUserService extends PsService implements BlockUserServiceInterface
{
    public function save($userData)
    {
        DB::beginTransaction();
        try{
            //prepare data
            $fromBlockUser = $this->prepareBlockUserData($userData['from_block_user_id'], $userData['to_block_user_id']);
            $fromBlockUser['added_user_id'] = $userData['from_block_user_id'];

            $toBlockUser = $this->prepareBlockUserData($userData['to_block_user_id'], $userData['from_block_user_id']);
            $toBlockUser['added_user_id'] = $userData['from_block_user_id'];

            //delete existing record
            $this->deleteBlockUser(null, $fromBlockUser);
            $this->deleteBlockUser(null, $toBlockUser);

            //add new record
            $this->saveBlockUser($fromBlockUser);
            $this->saveBlockUser($toBlockUser);

            PsCache::clear(ItemCache::BASE);

            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
            throw $e;
        }
    }

    public function delete($userData)
    {
        try{
            //prepare data
            $fromBlockUser = $this->prepareBlockUserData($userData['from_block_user_id'], $userData['to_block_user_id']);
            $toBlockUser = $this->prepareBlockUserData($userData['to_block_user_id'], $userData['from_block_user_id']);

            //delete existing record
            $this->deleteBlockUser(null, $fromBlockUser);
            $this->deleteBlockUser(null, $toBlockUser);

            PsCache::clear(ItemCache::BASE);

        }catch(\Throwable $e){
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relation = null)
    {
        return BlockUser::when($id, function($query, $id) {
                $query->where(BlockUser::id, $id);
            })
            ->when($conds, function($query, $conds) {
                $query->where($conds);
            })
            ->when($relation, function($query, $relation) {
                $query->with($relation);
            })
            ->first();
    }

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null)
    {
        $blockedUsers = BlockUser::when($relation, function ($query, $relation) {
                $query->with($relation);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
                $query->when(isset($conds['from_block_user_id']), function($q) use($conds) {
                    $q->where(BlockUser::t(BlockUser::addedUserId), $conds['from_block_user_id']);
                });
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $blockedUsers;
    }

    //////////////////////////////////////////////////////////////////////
    /// Private Functions
    //////////////////////////////////////////////////////////////////////

    ///-------------------------------------------------------------------
    /// Data Preparation
    ///-------------------------------------------------------------------
    private function prepareBlockUserData($fromBlockUserId, $toBlockUserId)
    {
        return [
            'from_block_user_id' => $fromBlockUserId,
            'to_block_user_id' => $toBlockUserId,
        ];
    }

    ///-------------------------------------------------------------------
    /// Database
    ///-------------------------------------------------------------------
    private function saveBlockUser($userData)
    {
        $blockedUser = new BlockUser();
        $blockedUser->fill($userData);
        $blockedUser->save();

        return $blockedUser;
    }

    private function deleteBlockUser($id = null, $conds = null)
    {
        $blockedUser = $this->get($id, $conds);
        if($blockedUser){
            $blockedUser->delete();
        }
    }

}
