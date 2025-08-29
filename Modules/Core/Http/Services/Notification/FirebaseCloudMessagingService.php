<?php
namespace Modules\Core\Http\Services\Notification;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Services\PsService;
use Google\Auth\OAuth2;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\UserAccessApiTokenService;

class FirebaseCloudMessagingService extends PsService implements FirebaseCloudMessagingServiceInterface
{
    protected $client;

    public function __construct(
        protected UserAccessApiTokenService $userAccessApiTokenService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected PushNotificationTokenServiceInterface $pushNotificationTokenService)
    {
        $this->client = new Client();
    }


    /**
     * Sending Message From FCM For Android & iOS By using topics subscribe
     */
    public function sendAndroidFcmTopicsSubscribe($data)
    {

        $jsonKey = $this->getFirebasePrivateKeyFile();

        $url = $this->getUrlForFCM($jsonKey['project_id']);

        $backend_setting = $this->backendSettingService->get();

        $fields = $this->handleAndroidTopicNoti($data, $backend_setting);

        $result = $this->sendFCMNotification($url, $fields);

        return $result;
    }

    /**
     * Sending Message From FCM For Frontend By using topics subscribe
     */
    public function sendAndroidFcmTopicsSubscribeFe($data, $prj_name)
    {

        $jsonKey = $this->getFirebasePrivateKeyFile();

        $url = $this->getUrlForFCM($jsonKey['project_id']);

        $backend_setting = $this->backendSettingService->get();

        $fields = $this->handleFETopicNoti($data, $backend_setting);
        $result = $this->sendFCMNotification($url, $fields);
        return $result;
    }

    public function sendAndroidFcm($registatoin_id, $data, $platform_names)
    {

        $message = $data['message'];
        $flag = $data['flag'];

        $prj_name = env('APP_URL');
        if (!str_ends_with($prj_name, '/')) {
            $prj_name = $prj_name . '/';
        }

        $click_action = "";

        foreach ($platform_names as $platform_name) {
            $currency_tmp =  '&currency=';
            $currency_tmp = htmlentities($currency_tmp);
            $click_action = $this->getClickAction($platform_name, $flag, $data, $prj_name);
        }

        $fields = $this->handleTokenNoti($flag, $message, $click_action, $registatoin_id, $data);

        $jsonKey = $this->getFirebasePrivateKeyFile();

        //Google cloud messaging GCM-API url
        $url = $this->getUrlForFCM($jsonKey['project_id']);

        $result = $this->sendFCMNotification($url, $fields);

        return $result;
    }

    public function getBearerTokenForFCMFromApi()
    {
        $data = $this->getToken();

        $dataArr = [
            "bearer_token_for_fcm" => $data["token"]
        ];
        return responseDataApi($dataArr);
    }

    public function topicSubscribeForNotiFromApi($topicSubscribeData)
    {
        $status = $this->handleTopicSubscribe($topicSubscribeData);

        if($status !== 200){
            throw new PsApiException("Subscribe topic process is failed.", Constants::badRequestStatusCode);
        }

        return responseMsgApi("Subscribe to ".$topicSubscribeData['topic']." process is success.", Constants::okStatusCode, Constants::successStatus);

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareNotiFieldData($messageData)
    {
        $fields = [
            'message' => $messageData,
        ];
        return $fields;
    }

    private function prepareTokenNotiMessageData($token, $notiArr, $notiData)
    {
        $message_data = [
            'token' => $token,
            'notification' => $notiArr,
            'data' => $notiData

        ];
        return $message_data;
    }

    private function prepareNotiMessageData($topic, $notiArr, $notiData)
    {
        $message_data = [
            'topic' => $topic,
            'notification' => $notiArr,
            'data' => $notiData,
        ];
        return $message_data;
    }

    private function prepareNotiArr($title, $body)
    {
        $noti_arr = [
            'title' => $title,
            'body' => $body
        ];
        return $noti_arr;
    }

    private function prepareTokenNotiData($type = null, $message = null, $flag = null, $clickAction = null, $rating = null, $buyerId = null, $sellerId = null, $itemId = null, $senderName = null, $senderProfilePhoto = null)
    {
        $notiData = [
            'message' => $message,
            'flag' => $flag,
            'sound' => 'default',
            'click_action' => $clickAction,
        ];

        if ($type === Constants::reviewNotiFlag) {
            $notiData['rating'] = (string)$rating;
        }

        if ($type === Constants::chatNotiFlag) {
            $notiData['buyer_id'] = (string)$buyerId;
            $notiData['seller_id'] = (string)$sellerId;
            $notiData['item_id'] = (string)$itemId;
            $notiData['sender_name'] = (string)$senderName;
            $notiData['sender_profile_photo'] = (string)$senderProfilePhoto;
            $notiData['action'] = 'abc';
        }

        return $notiData;
    }

    private function prepareNotiData($message, $clickAction, $flag, $itemId = null)
    {
        $noti_data = [
            'sound' => 'default',
            'message' => $message,
            'flag' => $flag,
            'click_action' => $clickAction,
        ];

        if (!empty($itemId)) {
            $noti_data['item_id'] = (string)$itemId;
        }

        return $noti_data;
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------

    private function handleAndroidTopicNoti($data, $backend_setting)
    {
        $message = $data['message'];

        if ($data['subscribe'] == 0 && $data['push'] == 1) {
            // push noti
            $click_action = $this->getAppURL() . "notification-list";
            $desc = $data['desc'];
            $topic = $backend_setting->topics;

            $noti_arr = $this->prepareNotiArr($message, $desc);

            $noti_data = $this->prepareNotiData($message, $click_action, ps_constant::broadcast);

        } else {
            $click_action = $this->getAppURL() . 'fe_item?item_id=' . $data['item_id'];
            $topic = $data['subcategory_id'] . '_MB';
            $itemId = $data['item_id'];

            $noti_arr = $this->prepareNotiArr(__('site_name'), $message);

            $noti_data = $this->prepareNotiData($message, $click_action, Constants::subscribeNotiFlag, $itemId);
        }

        $message_data = $this->prepareNotiMessageData($topic, $noti_arr, $noti_data);

        $fields = $this->prepareNotiFieldData($message_data);

        return $fields;
    }

    private function handleFETopicNoti($data, $backend_setting)
    {
        $message = $data['message'];

        if ($data['subscribe'] == 0 && $data['push'] == 1) {
            $click_action = $this->getAppURL() . '/' . 'notification';
            $desc = $data['desc'];
            $topic =  $backend_setting->topics_fe;

            // push noti
            $noti_arr = $this->prepareNotiArr($message, $desc);

            $noti_data = $this->prepareNotiData($message, $click_action, ps_constant::feBroadcast);

        } else {
            // subscribe noti
            $subscribeFlag = $data['subcategory_id'] . Constants::feSubscribeNotiFlag;
            $id = $data['item_id'];
            $title = Item::find($id)->title;
            $item_name = str_replace(' ', '%20', $title);
            $itm_name = str_replace(' ', '-', $title);
            $click_action = $this->getAppURL() . '/' . 'item/' . $itm_name . '?item_id=' . $data['item_id'] . '&item_name=' . $itm_name;
            $topic =  $backend_setting->topics_fe;
            $noti_arr = $this->prepareNotiArr(__('site_name'), $message);

            $noti_data = $this->prepareNotiData($message, $click_action, Constants::subscribeNotiFlag, $id);

        }

        $message_data = $this->prepareNotiMessageData($topic, $noti_arr, $noti_data);

        $fields = $this->prepareNotiFieldData($message_data);


        return $fields;
    }

    private function handleTokenNoti($flag, $message, $click_action, $registatoin_id, $data)
    {
        if ($flag == Constants::approvalNotiFlag || $flag == Constants::verifyBlueMarkNotiFlag || $flag == Constants::followNotiFlag) {

            $noti_arr = $this->prepareNotiArr(__('site_name'), $message);

            $noti_data = $this->prepareTokenNotiData(
                message: $message,
                flag: $flag,
                clickAction: $click_action
            );

        } elseif ($flag == Constants::reviewNotiFlag) {

            $rating = (string)$data['rating'];

            $noti_arr = $this->prepareNotiArr(__('site_name'), $message);

            $noti_data = $this->prepareTokenNotiData(
                type: Constants::reviewNotiFlag,
                message: $message,
                flag: Constants::reviewNotiFlag,
                clickAction: $click_action,
                rating: $rating
            );

        } else if ($flag == Constants::chatNotiFlag) {

            $message = $data['message'];
            $buyer_id = $data['buyer_user_id'];
            $seller_id = $data['seller_user_id'];
            $sender_name = $data['sender_name'];
            $item_id = $data['item_id'];
            $sender_profile_photo = $data['sender_profile_photo'];

            $noti_arr = $this->prepareNotiArr(__('site_name'), $message);

            $noti_data = $this->prepareTokenNotiData(
                type: Constants::chatNotiFlag,
                message: $message,
                flag: $flag,
                buyerId: $buyer_id,
                sellerId: $seller_id,
                itemId: $item_id,
                senderName: $sender_name,
                senderProfilePhoto: $sender_profile_photo,
                clickAction: $click_action
            );

        }

        $message_data = $this->prepareTokenNotiMessageData($registatoin_id, $noti_arr, $noti_data);

        $fields = $this->prepareNotiFieldData($message_data);

        return $fields;
    }

    private function sendFCMNotification($url, $fields)
    {
        $tokenForFCM = !empty($this->getToken()['token']) ? $this->getToken()['token'] : '';

        $headers = array(
            'Authorization: Bearer ' . $tokenForFCM,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        $response = json_decode($result, true);
        if (isset($response['error'])) {
          $error = $response['error'];

          if ($error['status'] === 'NOT_FOUND' &&
            isset($error['details'][0]['errorCode']) &&
            $error['details'][0]['errorCode'] === 'UNREGISTERED') {
                $invalidToken = $fields['message']['token'];
                $this->pushNotificationTokenService->delete(token: $invalidToken);
            }
        }

        curl_close($ch);

        return $result;
    }

    private function getUrlForFCM($firebaseProjectId)
    {
        $url = "https://fcm.googleapis.com/v1/projects/".$firebaseProjectId."/messages:send";
        return $url;
    }

    private function getToken()
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $jsonKey = $this->getFirebasePrivateKeyFile();

        $oauth2 = new OAuth2([
            'audience' => $jsonKey['token_uri'],
            'issuer' => $jsonKey['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $jsonKey['private_key'],
            'scope' => $scopes,
            'sub' => $jsonKey['client_email'],
            'tokenCredentialUri' => $jsonKey['token_uri'],
        ]);

        $accessToken = $oauth2->fetchAuthToken();

        if (!isset($accessToken['access_token'])) {
            throw new PsApiException("Error fetching OAuth2 access token.", Constants::badRequestStatusCode);
        }

        $dataArr = [
            "status" => "success",
            "code" => Constants::okStatusCode,
            "message" => "Token have been generated successfully",
            "token" => $accessToken['access_token']
        ];
        return $dataArr;

    }

    private function handleTopicSubscribe($topicSubscribeData)
    {
        $token = $topicSubscribeData['token'];
        $topic = $topicSubscribeData['topic'];

        $bearerToken = !empty($this->getToken()["token"]) ? $this->getToken()["token"] : '';
        $url = "https://iid.googleapis.com/iid/v1/".$token."/rel/topics/".$topic;

        $responseData = Http::withHeaders([
            'Authorization' => 'Bearer '.$bearerToken,
            'access_token_auth' => 'true'
        ])->post($url);

        $status = $responseData->status();

        return $status;
    }

    private function getAppURL()
    {
        $prj_name = env('APP_URL');
        if (!str_ends_with($prj_name, '/')) {
            $prj_name = $prj_name . '/';
        }
        return $prj_name;
    }

    private function getFirebasePrivateKeyFile()
    {
        $file = ps_constant::privateKeyFileNameForFCM;
        $filePath = base_path('storage/firebase/'.$file);

        if(!file_exists($filePath)){
            throw new PsApiException("The Private Json File is not found", Constants::notFoundStatusCode);
        }

        $jsonKey = json_decode(file_get_contents($filePath), true);

        if(empty($jsonKey)){
            throw new PsApiException("There is no content in this json file", Constants::badRequestStatusCode);
        }

        $jsonKey = json_decode(file_get_contents($filePath), true);
        return $jsonKey;
    }

    private function getFrontendClickAction($flag, $data, $prj_name)
    {
        switch ($flag) {
            case Constants::chatNotiFlag:
                return $prj_name . 'chat?buyer_user_id=' . $data['buyer_user_id'] . '&seller_user_id=' . $data['seller_user_id'] . '&item_id=' . $data['item_id'] . '&chat_flag=' . $data['chat_flag'];
            case Constants::reviewNotiFlag:
                return $prj_name . 'review-list?user_id=' . $data['review_user_id'];
            case Constants::approvalNotiFlag:
                return $prj_name . 'fe_item?item_id=' . $data['item_id'];
            case Constants::followNotiFlag:
            case Constants::verifyBlueMarkNotiFlag:
                return $prj_name . 'profile';
            default:
                return $prj_name . '';
        }
    }

    private function getClickAction($platform_name, $flag, $data, $prj_name)
    {
        switch (strtolower($platform_name)) {
            case 'frontend':
                return $this->getFrontendClickAction($flag, $data, $prj_name);
            case 'android':
            case 'ios':
                return ps_constant::flutterNotificationClick;
            default:
                return ps_constant::flutterNotificationClick;
        }
    }

}
