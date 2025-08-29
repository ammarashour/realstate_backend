<?php

namespace App\Http\Contracts\Notification;

use App\Http\Contracts\Core\PsInterface;

interface FirebaseCloudMessagingServiceInterface extends PsInterface
{
    public function sendAndroidFcmTopicsSubscribe($data);

    public function sendAndroidFcmTopicsSubscribeFe($data, $prj_name);

    public function sendAndroidFcm($registatoin_id, $data, $platform_names);

    public function getBearerTokenForFCMFromApi();

    public function topicSubscribeForNotiFromApi($topicSubscribeData);
}
