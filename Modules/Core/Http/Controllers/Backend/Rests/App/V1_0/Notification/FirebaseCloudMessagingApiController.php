<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Notification;

use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Notification\FCMTopicSubscribeRequest;

class FirebaseCloudMessagingApiController extends PsApiController
{
    public function __construct(
        protected Translator $translator,
        protected FirebaseCloudMessagingServiceInterface $firebaseCloudMessagingService
    ) 
    {
        parent::__construct();
    }

    public function getBearerTokenForFCM(Request $request)
    {
        return $this->firebaseCloudMessagingService->getBearerTokenForFCMFromApi($request);
    }

    public function topicSubscribeForNoti(FCMTopicSubscribeRequest $request)
    {
        $topicSubscribeData = $this->prepareDataTopicSubscribe($request);

        return $this->firebaseCloudMessagingService->topicSubscribeForNotiFromApi($topicSubscribeData);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareDataTopicSubscribe($request)
    {
        return [
            'topic' => $request->input('topic'),
            'token' => $request->input('token'),
        ];
    }
}
