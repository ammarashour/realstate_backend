<?php

namespace Modules\Core\Http\Services\User;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\User\UserBought;

class UserBoughtService extends PsService
{
    public function __construct()
    {
        $this->userBoughtApiRelations = ['item', 'buyer', 'seller'];

        $this->userBoughtIdCol = UserBought::id;
        $this->userBoughtBuyerUserIdCol = UserBought::buyerUserId;
        $this->userBoughtSellerUserIdCol = UserBought::sellerUserId;
        $this->userBoughtBuyerUserIdCol = UserBought::itemId;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $userBought = new UserBought();

            if (isset($request->item_id) && !empty($request->item_id))
                $userBought->item_id = $request->item_id;

            if (isset($request->buyer_user_id) && !empty($request->buyer_user_id))
                $userBought->buyer_user_id = $request->buyer_user_id;

            if (isset($request->seller_user_id) && !empty($request->seller_user_id))
                $userBought->seller_user_id = $request->seller_user_id;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $userBought->added_user_id = $request->added_user_id;
            else
                $userBought->added_user_id = Auth::user()->id;

            $userBought->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $userBought;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            $userBought = $this->getUserBought($id);

            if (isset($request->item_id) && !empty($request->item_id)) {
                $userBought->item_id = $request->item_id;
            }

            if (isset($request->buyer_user_id) && !empty($request->buyer_user_id)) {
                $userBought->buyer_user_id = $request->buyer_user_id;
            }

            if (isset($request->seller_user_id) && !empty($request->seller_user_id)) {
                $userBought->seller_user_id = $request->seller_user_id;
            }

            if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
                $userBought->updated_user_id = $request->updated_user_id;
            } else {
                $userBought->updated_user_id = Auth::user()->id;
            }

            $userBought->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $userBought;
    }

    public function destroy($id = null, $conds = null)
    {
        if($id){
            $userBought = UserBought::find($id);
            $userBought->delete();
        }

        if($conds){
            $userBoughts = $this->getUserBoughts(null, null, null, $conds);
            $userBoughtIds = $userBoughts->pluck($this->userBoughtIdCol);
            UserBought::destroy($userBoughtIds);
//            foreach($userBoughts as $userBought) {
//                $userBought->delete();
//            }
        }
    }

    public function getUserBoughts($relation = null, $limit = null, $offset = null, $conds = null)
    {
        $userBoughts = UserBought::when($relation, function ($q, $relation) {
                    $q->with($relation);
                })
                ->when($limit, function ($query, $limit) {
                    $query->limit($limit);
                })
                ->when($offset, function ($query, $offset) {
                    $query->offset($offset);
                })
                ->when($conds, function ($query, $conds) {
                    $query->where($conds);
                })
                ->latest()->get();
        return $userBoughts;
    }

    public function getUserBought($id = null, $conds = null, $relation = null)
    {
        $userBought = UserBought::when($id, function ($q, $id) {
                $q->where($this->userBoughtIdCol, $id);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
        return $userBought;
    }
}
