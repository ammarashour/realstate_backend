<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Notification;

use App\Exceptions\PsApiException;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Notification\ChatNotiServiceInterface;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Contracts\User\PushNotificationUserServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Notification\PushNotificationMessageApiResource;

class PushNotificationMessageApiController extends PsApiController
{
    protected $pushNotiMessageApiRelation;

    public function __construct(
        protected PushNotificationUserServiceInterface $pushNotificationUserService,
        protected PushNotificationMessageServiceInterface $pushNotificationMessageService,
        protected ChatNotiServiceInterface $chatNotiService,
        protected MobileSettingServiceInterface $mobileSettingService
    )
    {
        parent::__construct();
        $this->pushNotiMessageApiRelation = ['defaultPhoto'];
        parent::__construct();
    }

    public function allNotis(Request $request)
    {
        $response = collect();
        if(isset($request->login_user_id) && !empty($request->login_user_id)){
            $loginUserID = $request->login_user_id;
            $conds['login_user_id'] = $loginUserID;
            $chatMessages = $this->chatNotiService->getAll(conds: $conds, noPagination: Constants::yes);
        }

        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $notiIds = $this->pushNotificationUserService->getAll(
                $request->user_id,
                Constants::yes,
                Constants::yes
            )->pluck('noti_id');

        $pushNotis = $this->pushNotificationMessageService->getAll(
            relation: $this->pushNotiMessageApiRelation,
            limit: $limit,
            offset: $offset,
            notIds: $notiIds,
            noPagination: Constants::yes
        );

        foreach ($pushNotis as $pushNoti)
            $response->push($pushNoti);
        foreach ($chatMessages as $chatMessage)
            $response->push($chatMessage);
        // return $response->sortByDesc('added_date');
        $data = PushNotificationMessageApiResource::collection($response->sortByDesc('added_date'));

        return $this->handleNoDataResponse($offset, $data);
    }

    public function getNotiDetail(Request $request){
        // Get Noti Id
        $id = $this->getPushNotificationMessageId($request);

        // Get PushNotification Message Data
        $noti = $this->pushNotificationMessageService->get($id, $this->pushNotiMessageApiRelation);

        return responseDataApi(new PushNotificationMessageApiResource($noti));
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getPushNotificationMessageId($request)
    {
        $pushNotificationMessage = $this->pushNotificationMessageService->get($request->id);
        if (! empty($pushNotificationMessage)) {
            return $request->id;
        }

        $_err_message = __('core__api_record_not_found', [], $request->language_symbol);
        throw new PsApiException($_err_message, Constants::notFoundStatusCode);
    }

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }
}
