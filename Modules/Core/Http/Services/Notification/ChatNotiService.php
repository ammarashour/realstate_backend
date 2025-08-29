<?php
namespace Modules\Core\Http\Services\Notification;

use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Notification\ChatHistoryServiceInterface;
use App\Http\Contracts\Notification\ChatNotiServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Notification\ChatNoti;
use Modules\Core\Entities\Notification\PushNotificationMessage;

class ChatNotiService extends PsService implements ChatNotiServiceInterface
{


    public function __construct(
        protected ChatHistoryServiceInterface $chatHistoryService
    )
    {

    }

    public function save($chatNotiData, $loginUserId)
    {
        DB::beginTransaction();

        try {
            $chatNoti = new ChatNoti();
            $chatNoti->fill($chatNotiData);
            $chatNoti->added_user_id = $loginUserId;
            $chatNoti->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $chatNotiData, $loginUserId)
    {

        DB::beginTransaction();

        try {
            $chatNoti = $this->get($id);
            $chatNoti->fill($chatNotiData);
            $chatNoti->updated_user_id = $loginUserId;
            $chatNoti->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relation = null)
    {
        $chatNoti = ChatNoti::when($id, function ($q, $id) {

                $q->where(ChatNoti::id, $id);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();

        return $chatNoti;
    }

    public function getAll($conds = null, $relation = null, $limit = null, $offset = null, $pagPerPage = null, $noPagination = null)
    {
        $chatNotis = ChatNoti::when($relation, function ($q, $relation) {
                    $q->with($relation);
                })
                ->when($limit, function ($query, $limit) {
                    $query->limit($limit);
                })
                ->when($offset, function ($query, $offset) {
                    $query->offset($offset);
                })
                ->when($conds, function ($query, $conds) {
                    if(isset($conds['login_user_id']) && $conds['login_user_id']!=null){

                        $conds1[ChatNoti::buyerUserId] = $conds['login_user_id'];
                        $conds1[ChatNoti::chatFlag] = Constants::chatFromSeller;
                        if(isset($conds[ChatNoti::isRead]) && $conds[ChatNoti::isRead] !== null){
                            $conds1[ChatNoti::isRead] = $conds[ChatNoti::isRead];
                        }
                        if(isset($conds['item_id']) && $conds['item_id'] !== null){
                            $conds1['item_id'] = $conds['item_id'];
                        }
                        $query->where($conds1);

                        $conds2[ChatNoti::sellerUserId] = $conds['login_user_id'];
                        $conds2[ChatNoti::chatFlag] =  Constants::chatFromBuyer;
                        if(isset($conds[ChatNoti::isRead]) && $conds[ChatNoti::isRead] !== null){
                            $conds2[ChatNoti::isRead] = $conds[ChatNoti::isRead];
                        }
                        if(isset($conds['item_id']) && $conds['item_id'] !== null){
                            $conds2['item_id'] = $conds['item_id'];
                        }
                        $query->orWhere(function($query) use ($conds2){
                            $query->where($conds2);
                        });
                    }else{
                        if(isset($conds[ChatNoti::sellerUserId]) && $conds[ChatNoti::sellerUserId] !== null){
                            $conds2[ChatNoti::sellerUserId] = $conds[ChatNoti::sellerUserId];
                        }
                        if(isset($conds[ChatNoti::buyerUserId]) && $conds[ChatNoti::buyerUserId] !== null){
                            $conds2[ChatNoti::buyerUserId] = $conds[ChatNoti::buyerUserId];
                        }
                        if(isset($conds[ChatNoti::chatFlag]) && $conds[ChatNoti::chatFlag] !== null){
                            $conds2[ChatNoti::chatFlag] = $conds[ChatNoti::chatFlag];
                        }
                        if(isset($conds[ChatNoti::isRead]) && $conds[ChatNoti::isRead] !== null){
                            $conds2[ChatNoti::isRead] = $conds[ChatNoti::isRead];
                        }
                        if(isset($conds[ChatNoti::itemId]) && $conds[ChatNoti::itemId] !== null){
                            $conds2[ChatNoti::itemId] = $conds[ChatNoti::itemId];
                        }
                        $query->orWhere(function($query) use ($conds2){
                            $query->where($conds2);
                        });
                    }

                })
                ->latest();

                if ($pagPerPage) {
                    return $chatNotis->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    return $chatNotis->get();
                }

        return $chatNotis;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------


    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------


}
