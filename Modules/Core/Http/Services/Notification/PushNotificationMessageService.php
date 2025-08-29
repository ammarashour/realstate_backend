<?php
namespace Modules\Core\Http\Services\Notification;

use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Notification\PushNotificationMessage;

class PushNotificationMessageService extends PsService implements PushNotificationMessageServiceInterface
{
    protected $pushNotiMessageApiRelation, $coverImgType;

    public function __construct(
        protected ImageServiceInterface $imageService,
        protected FirebaseCloudMessagingServiceInterface $firebaseCloudMessagingService)
    {

        $this->coverImgType = 'push_notification_message';
        $this->pushNotiMessageApiRelation = ['defaultPhoto'];
    }

    public function save($pushNotificationMessageData, $pushNotificationMessageImage = null)
    {
        DB::beginTransaction();

        try {

            $pushNotificationMessage = $this->savePushNotificationMessage($pushNotificationMessageData);

            if(!empty($pushNotificationMessageImage)){
                $imgData = $this->prepareSaveImageData($pushNotificationMessage->id);
                $this->imageService->save($pushNotificationMessageImage, $imgData);
            }

            $this->sendPushNotiToAllUser($pushNotificationMessage);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function delete($id)
    {
        try {
            $this->imageService->deleteAll($id, Constants::pushNotificationMessageCoverImgType);

            $message = $this->deletePushNotificationMessage($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $message]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $relation = null){
        $push_notification_message = PushNotificationMessage::when($id, function ($q, $id){
                            $q->where(PushNotificationMessage::id, $id);
                        })
                        ->when($relation, function ($q, $relation){
                            $q->with($relation);
                        })
                        ->first();
        return $push_notification_message;
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $notIds=null,$noPagination = null,  $pagPerPage = null){

        $push_notification_messages = PushNotificationMessage::when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->when($limit, function ($query, $limit) {
                                    $query->limit($limit);
                                })
                                ->when($offset, function ($query, $offset) {
                                    $query->offset($offset);
                                })
                                ->when($conds, function ($query, $conds) {
                                    $query = $this->searching($query, $conds);
                                })
                                ->when($notIds, function ($query, $notIds) {
                                    $query->whereNotIn(PushNotificationMessage::id, $notIds);
                                })
                                ->when(empty($conds['order_by']), function ($query, $extra) {
                                    $query->orderBy(PushNotificationMessage::message, 'asc');

                                });

            if ($pagPerPage){
                $push_notification_messages = $push_notification_messages->paginate($pagPerPage)->onEachSide(1)->withQueryString();
            } elseif ($noPagination){
                $push_notification_messages = $push_notification_messages->get();
            }

        return $push_notification_messages;
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareSaveImageData($id)
    {
        return [
            'img_parent_id' => $id,
            'img_type' => Constants::pushNotificationMessageCoverImgType,
        ];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function searching($query, $conds){
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(PushNotificationMessage::tableName . '.' . PushNotificationMessage::message, 'like', '%' . $search . '%')
                        ->orWhere(PushNotificationMessage::tableName . '.' . PushNotificationMessage::description, 'like', '%' . $search . '%');
            });
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']){

            if($conds['order_by'] == 'id'){
                $query->orderBy('categories.id', $conds['order_type']);
            }
            else{
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    private function savePushNotificationMessage($pushNotificationMessageData)
    {
        $pushNotificationMessage = new PushNotificationMessage();
        $pushNotificationMessage->fill($pushNotificationMessageData);
        $pushNotificationMessage->added_user_id = Auth::user()->id;
        $pushNotificationMessage->save();

        return $pushNotificationMessage;
    }

    private function deletePushNotificationMessage($id)
    {
        $pushNotificationMessage = $this->get($id);
        $message = $pushNotificationMessage->message;
        $pushNotificationMessage->delete();

        return $message;
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------
    private function sendPushNotiToAllUser($pushNotificationMessage)
    {
        $data['subscribe'] = 0;
        $data['push'] = 1;
        $data['desc'] = $pushNotificationMessage->description;
        $data['message'] = $pushNotificationMessage->message;

        $this->firebaseCloudMessagingService->sendAndroidFcmTopicsSubscribe( $data );
        $this->firebaseCloudMessagingService->sendAndroidFcmTopicsSubscribeFe( $data ,env('APP_URL') );
    }
}
